<?php


namespace GeoSot\BaseAdmin\App\Traits\Controller;

trait HasAllowedActions
{

    protected $allowedActionsOnIndex = ['create', 'edit', 'enable', 'disable', 'delete', 'forceDelete', 'restore'];
    protected $allowedActionsOnCreate = ['save', 'saveAndClose', 'saveAndNew'];
    protected $allowedActionsOnEdit = ['save', 'saveAndClose', 'saveAndNew', 'makeNewCopy', 'delete'];


    /**
     * @param  null  $method
     * @param  array  $actions
     * @return bool
     */
    public function isAllowedAction($method = null, $actions = [])
    {
        foreach ($this->getAvailableActions($method, $actions) as $action) {
            if (in_array($action, $this->{"allowedActionsOn".ucfirst($method)})) {
                return true;
            }
        }
        return false;

    }

    /**
     * @param $method
     * @param $actions
     * @return array
     */
    protected function getAvailableActions($method, $actions): array
    {
        return $actions ?: $this->defaultAllowedActions($method);
    }

    /**
     * @param $method
     * @return array
     */
    private function defaultAllowedActions($method)
    {
        switch ($method) {
            case "create":
                return ['save', 'saveAndNew', 'SaveAndClose'];
            case "edit":
                return ['save', 'saveAndClose', 'saveAndNew', 'makeNewCopy'];
            default:
                return [];
        }
    }

}
