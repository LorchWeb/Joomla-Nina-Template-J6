<?php

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\Filesystem\File;
use Joomla\Filesystem\Folder;
use Joomla\CMS\Installer\InstallerScript;
use Joomla\CMS\Language\Text;

/**
 * Installation class to perform additional changes during install/uninstall/update
 *
 * @since  1.0.6v1
 */
class J51_NinaInstallerScript extends InstallerScript
{

	/**
	 * Version des Templates vor dem Update (aus manifest_cache).
	 *
	 * @var string
	 */
	protected $previousVersion = '';

	/**
	 * Merkt sich vor dem Update die bisher installierte Version.
	 */
	public function preflight($type, $installer)
	{
		if ($type === 'update')
		{
			try
			{
				$db    = Factory::getContainer()->get(\Joomla\Database\DatabaseInterface::class);
				$query = $db->getQuery(true)
					->select($db->quoteName('manifest_cache'))
					->from($db->quoteName('#__extensions'))
					->where($db->quoteName('type') . ' = ' . $db->quote('template'))
					->where($db->quoteName('element') . ' = ' . $db->quote('j51_nina'))
					->where($db->quoteName('client_id') . ' = 0');
				$cache = json_decode((string) $db->setQuery($query)->loadResult(), true);
				$this->previousVersion = \is_array($cache) ? (string) ($cache['version'] ?? '') : '';
			}
			catch (\Exception $e)
			{
				$this->previousVersion = '';
			}
		}

		return parent::preflight($type, $installer);
	}

	/**
	 * Alte Overrides (J3/frühe J4-Versionen) nur beim Update von Versionen vor 2.0.0 entfernen.
	 * Bei neueren Ständen liegen in diesen Ordnern nur noch kundeneigene Overrides – sie bleiben erhalten.
	 */
	protected function isLegacyUpdate($type): bool
	{
		return $type === 'update' && $this->previousVersion !== '' && version_compare($this->previousVersion, '2.0.0', '<');
	}

	/**
	 * Ensure the core templates are correctly moved to the new mode.
	 *
	 * @return  void
	 *
	 * @since   4.1.0
	 */
	protected function fixTemplateMode(): void
	{
		$db = Factory::getContainer()->get('DatabaseDriver');

		$template = 'j51_nina';
		$clientId = 0;
		$query = $db->getQuery(true)
			->update($db->quoteName('#__template_styles'))
			->set($db->quoteName('inheritable') . ' = 1')
			->where($db->quoteName('template') . ' = ' . $db->quote($template))
			->where($db->quoteName('client_id') . ' = ' . $clientId);
    
		try
		{
			$db->setQuery($query)->execute();
		}
		catch (Exception $e)
		{
			echo Text::sprintf('JLIB_DATABASE_ERROR_FUNCTION_FAILED', $e->getCode(), $e->getMessage()) . '<br>';

			return;
		}
	}
	
	public function postflight($type, $installer)
 	{
		$foldersToDelete = array(
			'/templates/j51_nina/html/com_contact',
			'/templates/j51_nina/html/com_finder',
			'/templates/j51_nina/html/com_media',
			'/templates/j51_nina/html/com_search',
			'/templates/j51_nina/html/com_users',
			'/templates/j51_nina/html/mod_breadcrumbs',
			'/templates/j51_nina/html/mod_custom',
			'/templates/j51_nina/html/mod_finder',
			'/templates/j51_nina/html/mod_login',
			'/templates/j51_nina/html/mod_search',
			'/templates/j51_nina/html/mod_tags_popular',
			'/templates/j51_nina/html/plg_content_pagenavigation',
			'/templates/j51_nina/html/plg_content_vote',
			'/templates/j51_nina/html/layouts/joomla/tinymce',
			'/templates/j51_nina/html/layouts/joomla/pagination',
			'/templates/j51_nina/html/layouts/joomla/form',
		);

		$filesToDelete = array(
			'/templates/j51_nina/html/modules.php',
			'/templates/j51_nina/html/mod_login/default_url.php',
			'/templates/j51_nina/html/mod_login/default_component.php',
			'/templates/j51_nina/html/layouts/joomla/content/tags.php',
			'/templates/j51_nina/html/layouts/joomla/content/readmore.php',
			'/templates/j51_nina/html/layouts/joomla/content/options_default.php',
			'/templates/j51_nina/html/layouts/joomla/content/language.php',
			'/templates/j51_nina/html/layouts/joomla/content/category_default.php',
			'/templates/j51_nina/html/layouts/joomla/content/blog_style_default_item_title.php',
			'/templates/j51_nina/html/layouts/joomla/content/intro_image.php',
			'/templates/j51_nina/html/layouts/joomla/content/full_image.php',
			'/templates/j51_nina/html/layouts/joomla/edit/frontediting_modules.php',
			'/templates/j51_nina/html/com_content//category/blog.php',
			'/templates/j51_nina/html/com_content//category/blog_item.php',
			'/templates/j51_nina/html/com_content/view.html.php',
			'/templates/j51_nina/html/mod_menu/default_url.php',
		);

		if (!$this->isLegacyUpdate($type))
		{
			$foldersToDelete = [];
			$filesToDelete   = [];
		}

		// Von 2.x mitgeliefertes Joomla-3-Override, ab 6.2.1 entfernt (immer löschen)
		$filesToDelete[] = '/templates/j51_nina/html/layouts/joomla/system/message.php';

		foreach ($foldersToDelete as $folder)
		{
			if (is_dir(JPATH_ROOT . $folder))
			{
				try
				{
					Folder::delete(JPATH_ROOT . $folder);
				}
				catch (\Exception $e)
				{
					// Ordner konnte nicht entfernt werden – Installation nicht abbrechen
				}
			}
		}

		foreach ($filesToDelete as $file)
		{
			if (is_file(JPATH_ROOT . $file))
			{
				try
				{
					File::delete(JPATH_ROOT . $file);
				}
				catch (\Exception $e)
				{
					// Datei konnte nicht entfernt werden – Installation nicht abbrechen
				}
			}
		}

		// Ensure templates are moved to the correct mode
		$this->fixTemplateMode();
 	}
}