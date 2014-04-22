<?php
/**
*
* @package Board Rules Extension
* @copyright (c) 2013 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace phpbb\boardrules;

/**
* Extension class for custom enable/disable/purge actions
*
* NOTE TO EXTENSION DEVELOPERS:
* Normally it is not necessary to define any functions inside the ext class below.
* The ext class may contain special (un)installation commands in the methods
* enable_step(), disable_step() and purge_step(). As it is, these methods are defined
* in phpbb_extension_base, which this class extends, but you can overwrite them to
* give special instructions for those cases. Board Rules must do this because it uses
* the notifications system, which requires the methods enable_notifications(),
* disable_notifications() and purge_notifications() be run to properly manage the
* notifications created by Board Rules when enabling, disabling or deleting this
* extension.
*/
class ext extends \phpbb\extension\base
{	
	/**
	* Single enable step that installs any included migrations
	*
	* @param mixed $old_state State returned by previous call of this method
	* @return false Indicates no further steps are required
	*/
	function enable_step($old_state)
	{
		switch ($old_state)
		{
			case '': // Empty means nothing has run yet

				//enable notifications
				$phpbb_notifications = $this->container->get('notification_manager');
				$phpbb_notifications->enable_notifications('boardrules');
				return 'notifications';

			break;

			default:

				return parent::enable_step($old_state);

			break;
		}
	}

	/**
	* Single disable step that does nothing
	*
	* @param mixed $old_state State returned by previous call of this method
	* @return false Indicates no further steps are required
	*/
	function disable_step($old_state)
	{
		switch ($old_state)
		{
			case '': // Empty means nothing has run yet

				// Disable board rules notifications
				$phpbb_notifications = $this->container->get('notification_manager');
				$phpbb_notifications->disable_notifications('boardrules');
				return 'notifications';

			break;

			default:

				return parent::disable_step($old_state);

			break;
		}
	}

	/**
	* Single purge step that reverts any included and installed migrations
	*
	* @param mixed $old_state State returned by previous call of this method
	* @return false Indicates no further steps are required
	*/
	function purge_step($old_state)
	{
		switch ($old_state)
		{
			case '': // Empty means nothing has run yet

				try
				{
					// Purge board rules notifications
					$phpbb_notifications = $this->container->get('notification_manager');
					$phpbb_notifications->purge_notifications('boardrules');	
				}
				catch (\phpbb\notification\exception $e)
				{
					// continue
				}

				return 'notifications';

			break;

			default:

				return parent::purge_step($old_state);

			break;
		}
	}
}
