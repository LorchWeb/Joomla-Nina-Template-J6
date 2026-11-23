[CmdletBinding()]
param(
    [string]$OutputDirectory
)

$ErrorActionPreference = 'Stop'

$projectRoot = [System.IO.Path]::GetFullPath((Split-Path $PSScriptRoot -Parent))
$manifestPath = Join-Path $projectRoot 'templateDetails.xml'
[xml]$manifest = Get-Content -LiteralPath $manifestPath -Raw -Encoding UTF8
$version = $manifest.extension.SelectSingleNode('version').InnerText.Trim()

if ([string]::IsNullOrWhiteSpace($version)) {
    throw 'Im Manifest wurde keine Templateversion gefunden.'
}

if ([string]::IsNullOrWhiteSpace($OutputDirectory)) {
    $OutputDirectory = Join-Path (Split-Path $projectRoot -Parent) 'build'
}

$outputRoot = [System.IO.Path]::GetFullPath($OutputDirectory)
$stageRoot = [System.IO.Path]::GetFullPath((Join-Path $outputRoot "release-stage-j51_nina-$version"))
$zipPath = [System.IO.Path]::GetFullPath((Join-Path $outputRoot "j51_nina_$version-joomla6.zip"))

function Assert-ChildPath {
    param(
        [Parameter(Mandatory)]
        [string]$Path,
        [Parameter(Mandatory)]
        [string]$Parent
    )

    $parentPrefix = $Parent.TrimEnd([System.IO.Path]::DirectorySeparatorChar) + [System.IO.Path]::DirectorySeparatorChar

    if (-not $Path.StartsWith($parentPrefix, [System.StringComparison]::OrdinalIgnoreCase)) {
        throw "Pfad liegt außerhalb des erlaubten Build-Verzeichnisses: $Path"
    }
}

Assert-ChildPath -Path $stageRoot -Parent $outputRoot
Assert-ChildPath -Path $zipPath -Parent $outputRoot

New-Item -ItemType Directory -Force -Path $outputRoot | Out-Null

if (Test-Path -LiteralPath $stageRoot) {
    Remove-Item -LiteralPath $stageRoot -Recurse -Force
}

if (Test-Path -LiteralPath $zipPath) {
    Remove-Item -LiteralPath $zipPath -Force
}

New-Item -ItemType Directory -Path $stageRoot | Out-Null

$excludedRelativePaths = [System.Collections.Generic.HashSet[string]]::new(
    [System.StringComparer]::OrdinalIgnoreCase
)
[void]$excludedRelativePaths.Add('css/custom.css')
[void]$excludedRelativePaths.Add('media/css/custom.css')

function Copy-ReleaseFile {
    param(
        [Parameter(Mandatory)]
        [string]$Source,
        [Parameter(Mandatory)]
        [string]$RelativePath
    )

    $normalisedRelativePath = $RelativePath.Replace('\', '/')

    if ($excludedRelativePaths.Contains($normalisedRelativePath)) {
        return
    }

    if (-not (Test-Path -LiteralPath $Source -PathType Leaf)) {
        throw "Manifestdatei fehlt: $normalisedRelativePath"
    }

    $destination = Join-Path $stageRoot $RelativePath
    $destinationDirectory = Split-Path $destination -Parent
    New-Item -ItemType Directory -Force -Path $destinationDirectory | Out-Null
    Copy-Item -LiteralPath $Source -Destination $destination -Force
}

function Copy-ReleaseDirectory {
    param(
        [Parameter(Mandatory)]
        [string]$RelativeDirectory
    )

    $sourceDirectory = Join-Path $projectRoot $RelativeDirectory

    if (-not (Test-Path -LiteralPath $sourceDirectory -PathType Container)) {
        throw "Manifestverzeichnis fehlt: $RelativeDirectory"
    }

    foreach ($file in Get-ChildItem -LiteralPath $sourceDirectory -Recurse -File) {
        $relativeFile = [System.IO.Path]::GetRelativePath($projectRoot, $file.FullName)
        Copy-ReleaseFile -Source $file.FullName -RelativePath $relativeFile
    }
}

foreach ($filename in $manifest.extension.files.filename) {
    $relativeFile = [string]$filename
    Copy-ReleaseFile -Source (Join-Path $projectRoot $relativeFile) -RelativePath $relativeFile
}

$scriptFile = [string]$manifest.extension.scriptfile

if (-not [string]::IsNullOrWhiteSpace($scriptFile)) {
    Copy-ReleaseFile -Source (Join-Path $projectRoot $scriptFile) -RelativePath $scriptFile
}

foreach ($folder in $manifest.extension.files.folder) {
    Copy-ReleaseDirectory -RelativeDirectory ([string]$folder).TrimEnd('/', '\')
}

$mediaFolder = $manifest.extension.media.GetAttribute('folder')

# Nina liefert die Medien im Hauptverzeichnis aus (css, js, images, fonts); ein eigener media-Ordner existiert nicht.
if (-not [string]::IsNullOrWhiteSpace($mediaFolder) -and (Test-Path -LiteralPath (Join-Path $projectRoot $mediaFolder) -PathType Container)) {
    Copy-ReleaseDirectory -RelativeDirectory $mediaFolder.TrimEnd('/', '\')
}

Add-Type -AssemblyName System.IO.Compression.FileSystem
[System.IO.Compression.ZipFile]::CreateFromDirectory(
    $stageRoot,
    $zipPath,
    [System.IO.Compression.CompressionLevel]::Optimal,
    $false
)

$archive = [System.IO.Compression.ZipFile]::OpenRead($zipPath)

try {
    $entries = @($archive.Entries | ForEach-Object { $_.FullName.Replace('\', '/') })
    $forbiddenEntries = @($entries | Where-Object { $excludedRelativePaths.Contains($_) })

    if ($forbiddenEntries.Count -gt 0) {
        throw "Kundenspezifische Custom-CSS-Dateien wurden in das ZIP aufgenommen: $($forbiddenEntries -join ', ')"
    }

    if ($entries -notcontains 'templateDetails.xml') {
        throw 'Das Release-ZIP enthält kein templateDetails.xml im Archivstamm.'
    }

    if ($entries -notcontains 'update.php') {
        throw 'Das Release-ZIP enthält das Installationsskript update.php nicht.'
    }
}
finally {
    $archive.Dispose()
}

$zip = Get-Item -LiteralPath $zipPath
$hash = Get-FileHash -LiteralPath $zipPath -Algorithm SHA256

[pscustomobject]@{
    Version = $version
    Stage = $stageRoot
    Zip = $zip.FullName
    Bytes = $zip.Length
    Sha256 = $hash.Hash
    Entries = $entries.Count
    Excluded = @($excludedRelativePaths)
}
