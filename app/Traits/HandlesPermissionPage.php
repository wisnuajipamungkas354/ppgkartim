<?php

namespace App\Traits;

use App\Helpers\RolePermission;

trait HandlesPermissionPage
{
  protected static function permissionKey(): string
  {
      return 'page_' . static::getPageSlug();
  }

  protected static function getPageSlug(): string
  {
      return str_replace('Page', '', class_basename(static::class));
  }

  /**
     * Override canAccess bawaan Filament Page
     */
  public static function canAccess(): bool
  {
      return RolePermission::can(static::permissionKey());
  }
}