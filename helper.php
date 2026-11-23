<?php

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Session\Session;
use Joomla\Database\DatabaseInterface;

class TplJ51NinaHelper
{
	public static function importAjax()
	{
		self::checkAccess();
		$input = Factory::getApplication()->getInput();
		$contents = $input->json->getRaw();
		// Check if it is json content
		$contents = @json_encode(@json_decode($contents));
		if (!$contents)
		{
			throw new Exception('No content available or an invalid format is uploaded');
		}
		$db = Factory::getContainer()->get(DatabaseInterface::class);
		$db->setQuery('update #__template_styles set params = ' . $db->quote($contents) . ' where id = ' . $input->getInt('id'));
		$db->execute();
		Factory::getApplication()->enqueueMessage('Uploaded successfully settings');
	}
	public static function exportAjax()
	{
		self::checkAccess();
		$db = Factory::getContainer()->get(DatabaseInterface::class);
		$query = $db->getQuery(true)
			->select('*')
			->from('#__template_styles')
			->where('id = ' . Factory::getApplication()->getInput()->getInt('id'));
		$db->setQuery($query);
		$style = $db->loadObject();
		// Download the ical content
		header('Content-Type: application/json');
		header('Content-disposition: attachment; filename="' . $style->template . '-preset.json"');
		return $style->params;
	}
	public static function loadPresetAjax()
	{
		self::checkAccess();
		$input = Factory::getApplication()->getInput();
		$path = JPATH_ROOT . '/templates/' . $input->get('template') . '/presets/' . $input->get('preset') . '.json';
		if (!file_exists($path))
		{
			throw new Exception('File ' . (JDEBUG ? $path : '')  . ' does not exists!');
		}
		$preset = file_get_contents($path);
		$db = Factory::getContainer()->get(DatabaseInterface::class);
		$db->setQuery('update #__template_styles set params = ' . $db->quote($preset) . ' where id = ' . $input->getInt('id'));
		$db->execute();
		Factory::getApplication()->enqueueMessage('Applied successfully preset ' . $input->get('preset'));
	}
	private static function checkAccess()
	{
		if (!Session::checkToken('get'))
		{
			throw new Exception(Text::_('JINVALID_TOKEN'), 403);
		}
		if (!Factory::getApplication()->getIdentity()->authorise('core.create', 'com_templates'))
		{
			throw new \Exception('Not allowed!!', 403);
		}
	}
}