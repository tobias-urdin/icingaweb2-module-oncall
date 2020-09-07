<?php
namespace Icinga\Module\Oncall\Forms;

use Icinga\Forms\ConfigForm;
use Icinga\Module\Monitoring\Backend\MonitoringBackend;

class OnCallConfigForm extends ConfigForm
{
	/** @var MonitoringBackend */
	protected $backend;

	/** @var DbConnection */
	protected $db;

	/**
	 * Initialize this form
	 */
	public function init()
	{
		$this->setName('form_oncall_config');
		$this->setSubmitLabel($this->translate('Save Config'));

		if ($this->backend === null) {
			$this->backend = MonitoringBackend::instance();
		}

		if ($this->db === null && $this->backend !== null) {
			$this->db = $this->backend->getResource();
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function createElements(array $formData)
	{
		$query = $this->db->select();
		$query->select()->from('icinga_contacts')->order('alias');

		$result = $this->db->fetchAll($query);

		$contacts = [];

		foreach($result as $r) {
			if (empty($r->pager_address))
				continue;

			$contacts[$r->pager_address] = $r->alias . ' ' . $r->pager_address;
		}

		$this->addElement(
			'select',
			'oncall_contact',
			[
				'required'     => true,
				'label'        => $this->translate('Contact'),
				'description'  => $this->translate('The contact to set as oncall.'),
				'multiOptions' => $contacts,
			]
		);

		$this->addElement(
			'checkbox',
			'oncall_enabled',
			[
				'required'    => false,
				'label'       => $this->translate('Enable notifications'),
				'description' => $this->translate('If notifications should be enabled.'),
			]
		);
	}
}
