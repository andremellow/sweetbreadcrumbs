<?php

namespace App\Services;

use App\Enums\ConfigEnum;
use App\Models\Config;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Query\JoinClause;

class ConfigService
{
    public function __construct(
        protected OrganizationService $organizationService, 
    ) {}

    public function get(ConfigEnum $key)
    {
        $config = Config::rightJoin('config_defaults', function (JoinClause $join) {
                    $join->on('configs.config_default_id','=', 'config_defaults.id')
                        ->where('configs.organization_id', $this->organizationService->getOrganization()->id);
                })->where('config_defaults.key', $key->value)
                ->select('configs.value', 'config_defaults.value as default')
                ->first();
        
        return $this->valueOrDefault($key, $config);
    }

    protected function valueOrDefault(ConfigEnum $key, Config $config): ?string
    {
        if($config->value !== null) {
            return $config->value;
        }

        switch ($key) {
            case ConfigEnum::TASK_DEFAULT_PRIORITY_ID:
                return $this->getTaskDefaultPriorityId($config->default);
                break;
            
            default:
                return $config->default;
                break;
        }
    }

    public function getTaskDefaultPriorityId(string $priorityName)
    {
        $priority = $this->organizationService->getOrganization()->priorities()->where('name', $priorityName)->first();

        if(!$priority) {
            $priority = $this->organizationService->getOrganization()->priorities()->first();
        }

        return $priority->id;
    }
}
