<?php
namespace Icinga\Module\Oncall\Controllers;

use Icinga\Web\Controller;
use Icinga\Module\Monitoring\Backend\MonitoringBackend;
use Icinga\Data\Filter\Filter;
use Icinga\Module\Monitoring\Command\Transport\CommandTransport;
use Icinga\Module\Monitoring\Command\Object\AcknowledgeProblemCommand;
use Icinga\Module\Monitoring\Object\HostList;
use Icinga\Module\Monitoring\Object\ServiceList;

/**
 * Oncall module incoming
 */
class IncomingController extends Controller
{
	protected $backend = null;
	protected $transport = null;

	protected $requiresAuthentication = false;
	protected $innerLayout = 'inline';
	protected $inlineLayout = 'inline';
	protected $autorefreshInterval = 0;

	public function init()
	{
		parent::init();

		if ($this->backend === null) {
			$this->backend = MonitoringBackend::instance();
		}

		if ($this->transport === null) {
			$this->transport = new CommandTransport();
		}
	}

	private function _getHostList()
	{
		$hostList = new HostList($this->backend);
		$hostList->addFilter(Filter::where('host_problem', 1));

		$hostList->setColumns([
			'host_acknowledged',
			'host_active_checks_enabled',
			'host_display_name',
			'host_event_handler_enabled',
			'host_flap_detection_enabled',
			'host_handled',
			'host_in_downtime',
			'host_is_flapping',
			'host_last_state_change',
			'host_name',
			'host_notifications_enabled',
			'host_obsessing',
			'host_passive_checks_enabled',
			'host_problem',
			'host_state',
			'instance_name'
		]);

		$unhandledHosts = $hostList->getUnhandledObjects();
		return $unhandledHosts;
	}

	private function _getServiceList()
	{
		$serviceList = new ServiceList($this->backend);
		$serviceList->addFilter(Filter::where('service_problem', 1));

		$serviceList->setColumns([
			'host_name',
			'host_handled',
			'host_display_name',
			'host_problem',
			'host_state',
			'instance_name',
			'service_display_name',
			'service_description',
			'service_acknowledged',
			'service_active_checks_enabled',
			'service_event_handler_enabled',
			'service_flap_detection_enabled',
			'service_handled',
			'service_in_downtime',
			'service_is_flapping',
			'service_last_state_change',
			'service_notifications_enabled',
			'service_obsessing',
			'service_passive_checks_enabled',
			'service_problem',
			'service_state'
		]);

		$unhandledServices = $serviceList->getUnhandledObjects();
		return $unhandledServices;
	}

	private function _acknowledgeProblem($object)
	{
		$ack = new AcknowledgeProblemCommand();

		$ack->setObject($object)
			->setComment('Acknowledged by OnCall')
			->setAuthor('OnCall')
			->setPersistent(false)
			->setSticky(false)
			->setNotify(false);

		try {
			$this->transport->send($ack);
		} catch (Exception $e) {
			return false;
		}

		return true;
	}

	/**
	 * index action
	 */
	public function indexAction()
	{
		foreach($this->_getHostList() as $h) {
			$this->_acknowledgeProblem($h);
		}

		foreach($this->_getServiceList() as $s) {
			$this->_acknowledgeProblem($s);
		}
	}
}
