<?php

namespace App\Livewire\Workstream;

use /**
 * Data Transfer Object for handling the deletion of a workstream.
 *
 * This DTO is responsible for encapsulating the data
 * associated with a workstream deletion request and ensuring
 * data integrity during the operation.
 *
 * Application: Sweet Bread Crumbs
 * Framework: Laravel v12.2.0
 * Database: PostgreSQL
 * Queue Connection: Sync
 *
 * Responsibility:
 * - To manage and transfer data required for deleting a workstream.
 *
 * Usage Context:
 * - Typically utilized in service or controller layers where deletion of a workstream is necessary.
 */
App\DTO\Workstream\DeleteWorkstreamDTO;
use /**
 * Enum class representing various events in the "Sweet Bread Crumbs" application.
 *
 * This class defines the set of possible events that can be used
 * throughout the application, offering a centralized and standardized
 * way to reference these values.
 *
 */
App\Enums\EventEnum;
use /**
 * Trait WithSorting.
 *
 * This trait provides functionality to handle sorting logic within components.
 * It is typically used with Laravel Livewire components to manage sorting state
 * and apply sorting to database queries or collections.
 *
 * Usage in the "Sweet Bread Crumbs" application should comply with
 * the application standards and architecture.
 *
 */
App\Livewire\Traits\WithSorting;
use /**
 * The Workstream model represents a workstream within the "Sweet Bread Crumbs" Laravel application.
 *
 * This model interacts with the PostgreSQL database connection configured for the application.
 * It utilizes the default Eloquent ORM provided by Laravel to perform database operations.
 *
 * Key Features:
 * - Integration with PostgreSQL database (`pgsql` connection).
 * - Designed to handle workstream-related data.
 * - Leverages Laravel's built-in functionalities such as mass assignment protection, relationships,
 *   and attribute casting.
 *
 * Responsibilities:
 * 1. Define the database table associated with the workstream model, if applicable.
 * 2. Specify relationships to other models like users, tasks, or projects within the application.
 * 3. Implement model-specific business logic or custom accessors/mutators.
 *
 * Compatibility:
 * - Requires Laravel v12.2.0 or higher.
 *
 * Queues:
 * - This feature utilizes the `sync` queueing connection for any event dispatching or job handling
 *   associated with the model.
 */
App\Models\Workstream;
use /**
 * UserService is a service layer responsible for managing user-related operations
 * within the "Sweet Bread Crumbs" Laravel application.
 *
 * This service interacts with the PostgreSQL database configured for the application.
 * It relies on the 'sync' queue connection for any deferred or queued tasks.
 *
 * Core functionality includes managing user data, performing business logic related to
 * user activities, and serving as an intermediary between controllers and repositories.
 *
 * This class adheres to Laravel's service layer principles, ensuring a clean separation
 * of concerns and promoting maintainability.
 */
App\Services\UserService;
use /**
 * Service class responsible for handling operations related to workstreams
 * in the "Sweet Bread Crumbs" Laravel application.
 *
 * This service interacts with the application database (PostgreSQL)
 * and provides methods to manage workstream functionalities.
 * It may also utilize synchronous queues for specific tasks.
 *
 */
App\Services\WorkstreamService;
use /**
 * Interface LengthAwarePaginator.
 *
 * This interface is a contract for creating length-aware pagination instances
 * in the Laravel framework. It provides methods for retrieving information about
 * the current pagination state, such as total items, items per page, current page,
 * and the ability to iterate over the paginated items.
 *
 * It is typically used for paginating collections or query results in a structured
 * and consistent manner.
 *
 * Applicable to Laravel version: 12.2.0
 */
Illuminate\Contracts\Pagination\LengthAwarePaginator;
use /**
 * Interface Contract for a View in Laravel.
 *
 * This interface represents a view instance in a Laravel application, allowing the creation of views,
 * the passing of data to the views, and rendering views into response-ready formats.
 *
 * The application "Sweet Bread Crumbs" uses this interface for rendering templates efficiently
 * and adheres to a sync queue connection mechanism while interacting with the PostgreSQL database.
 *
 * Responsibilities:
 * - Manage the view rendering process.
 * - Provide methods for dynamically binding data to views.
 * - Handle the lifecycle of view outputs.
 *
 * It is extensively used in the Laravel application workflow for templating mechanisms.
 */
Illuminate\Contracts\View\View;
use /**
 * The Auth facade provides access to the authentication services
 * in the Laravel application "Sweet Bread Crumbs".
 *
 * This facade acts as a proxy to the underlying authentication functionalities,
 * such as logging in users, checking if a user is authenticated, retrieving the
 * currently authenticated user, or logging a user out.
 *
 * The application uses PostgreSQL (pgsql) as its database connection and has
 * a queue connection configured to 'sync'.
 *
 * Key Features:
 * - Retrieve the currently authenticated user's details.
 * - Authenticate users and manage their sessions.
 * - Support for user roles and permissions, if integrated.
 * - Ability to guard certain routes based on the authentication state.
 *
 * Note:
 * Ensure proper middleware is applied in routes or controllers to enforce
 * authentication where required.
 */
Illuminate\Support\Facades\Auth;
use /**
 * This attribute is used to register a listener for a specific event in a Livewire component.
 * The associated method in the component will be triggered when the specified event occurs.
 *
 * Usage of the `On` attribute streamlines event handling in Livewire components.
 * It provides a declarative way to specify that a method should execute
 * in response to a particular event.
 *
 */
Livewire\Attributes\On;
use /**
 * Class Url.
 *
 * This class is part of the Livewire framework attributes.
 * It is used to define and handle URL-related attributes and functionality
 * within the Livewire context of a Laravel application.
 *
 */
Livewire\Attributes\Url;
use /**
 * This is a Livewire component for the "Sweet Bread Crumbs" Laravel application.
 *
 * Laravel Version: v12.2.0
 * Database Connection: pgsql
 * Queue Connection: sync
 *
 * This component is part of the application's dynamic frontend functionality.
 */
Livewire\Component;
use /**
 * Trait Livewire\WithPagination.
 *
 * This trait provides pagination functionality for Livewire components.
 * It simplifies the implementation of paginated data in Livewire-based applications.
 *
 * The trait uses Livewire's built-in capabilities to manage pagination state automatically.
 * It injects pagination parameters into the component and handles common tasks
 * such as resetting the page index when filtering or modifying the associated data.
 *
 * The following functionalities are enabled when using this trait:
 * - Dynamic updates to pagination state based on user interaction.
 * - Auto-resets pagination when filters or search queries are adjusted.
 * - Seamless integration with Laravel's pagination mechanisms.
 *
 * Requirements:
 * - Ensure the underlying model or collection supports Laravel's `paginate()` method.
 * - Define per-page limits and adjustments within your Livewire components, as necessary.
 */
Livewire\WithPagination;

/**
 * Component for listing and managing workstreams.
 *
 * Handles filtering, sorting, deletion, and listing of workstreams within
 * a given organization.
 */
#[On([EventEnum::WORKSTREAM_CREATED->value, EventEnum::WORKSTREAM_UPDATED->value])]
class ListWorkstreams extends Component
{
    use WithPagination, WithSorting;

    #[Url()]
    public ?string $name = null;

    #[Url()]
    public ?int $priorityId = null;

    public bool $isFiltred = false;

    public function applyFilter(): void {}

    #[On(EventEnum::RESET->value)]
    public function resetForm(): void
    {
        $this->reset('name', 'priorityId');
    }

    protected function list(UserService $userService, WorkstreamService $workstreamService): LengthAwarePaginator
    {

        return $workstreamService->list(
            $userService->getCurrentOrganization(),
            $this->name,
            $this->priorityId,
            $this->sortBy,
            $this->sortDirection
        );
    }

    public function delete(WorkstreamService $workstreamService, int $workstreamId): void
    {
        $workstreamService->delete(
            Auth::user(),
            new DeleteWorkstreamDTO(
                // THIS IS TERRIBLE
                workstream: Workstream::findOrFail($workstreamId)
            )
        );

        $this->dispatch(EventEnum::WORKSTREAM_DELETED->value, workstreamId: $workstreamId);
    }

    public function render(UserService $userService, WorkstreamService $workstreamService): View
    {
        $this->isFiltred = ! empty($this->name) || ! empty($this->priorityId);

        return view('livewire.workstream.list-workstreams', [
            'organization' => $userService->getCurrentOrganization(),
            'workstreams' => $this->list($userService, $workstreamService),
        ]);
    }
}
