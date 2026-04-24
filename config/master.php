<?php

return [
 'user' => [
  'view' => 'pages.master.user.index',
  'model' => App\Models\User::class,
  'with' => ['roles'],
 ],
 'role' => [
  'view' => 'pages.master.role.index',
  'model' => Spatie\Permission\Models\Role::class,
 ],
 'storage' => [
  'view' => 'pages.master.storage.index',
  'model' => App\Models\Storage::class,
 ],
 'storage-rack' => [
  'view' => 'pages.master.storage-rack.index',
  'model' => App\Models\StorageRack::class,
  'with' => ['storage'],
 ],
 'blood-pack' => [
  'view' => 'pages.master.blood-pack.index',
 ],
];
