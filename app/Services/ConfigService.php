<?php

namespace App\Services;

use App\Enums\ConfigEnum;
use App\Models\Config;
use Illuminate\Database\Query\JoinClause;

class ConfigService
{
    public function __construct(
        protected UserService $userService,
    ) {}

    public function get(ConfigEnum $key)
    {
        $config = $this->getConfigWithDefaultByKey($key);

        return $this->cast(
            $key,
            $this->valueOrDefault($key, $config)
        );
    }

    protected function cast(ConfigEnum $key, $value)
    {
        switch ($key->type()) {
            case 'int':
                return intval($value);
                // @codeCoverageIgnoreStart
            default:
                return $value;
                // @codeCoverageIgnoreEnd
        }
    }

    public function getConfigWithDefaultByKey(ConfigEnum $key)
    {
        return Config::rightJoin('config_defaults', function (JoinClause $join) {
            $join->on('configs.config_default_id', '=', 'config_defaults.id')
                ->where('configs.organization_id', $this->userService->getCurrentOrganization()->id);
        })->where('config_defaults.id', $key->value)
            ->select('configs.value', 'config_defaults.value as default')
            ->first();
    }

    public function valueOrDefault(ConfigEnum $key, Config $config): ?string
    {
        if ($config->value !== null) {
            return $config->value;
        }

        switch ($key) {
            case ConfigEnum::TASK_DEFAULT_PRIORITY_ID:
                return $this->getTaskDefaultPriorityId($config->default);
                break;
            case ConfigEnum::WORKSTREAM_DEFAULT_PRIORITY_ID:
                return $this->getTaskDefaultPriorityId($config->default);
                break;

            default:
                return $config->default;
                break;
        }
    }

    public function getTaskDefaultPriorityId(string $priorityName)
    {
        $priority = $this->userService->getCurrentOrganization()->priorities()->where('name', $priorityName)->first();

        if (! $priority) {
            $priority = $this->userService->getCurrentOrganization()->priorities()->first();
        }

        return $priority->id;
    }
}
