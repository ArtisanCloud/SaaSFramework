<?php
declare(strict_types=1);

namespace ArtisanCloud\SaaSFramework\Services\TenantService\src\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
  // Disable Laravel's mass assignment protection
  protected $guarded = [];
}
