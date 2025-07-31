<?php

namespace App\Traits;

use App\Helpers\RolePermission;

trait HandlesPermissionWidget
{
  protected static function permissionKey(): string
  {
      return 'widget_' . static::getWidgetSlug();
  }

  protected static function getWidgetSlug(): string
  {
      return str_replace('Widget', '', class_basename(static::class));
  }

  /**
     * Override canView bawaan Filament Widget
     */
  public static function canView(): bool
  {
      return RolePermission::can(static::permissionKey());
  }
}