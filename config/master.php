<?php

return [
 'blood-pack' => [
  'view' => 'pages.master.blood-pack.index',
  'model' => App\Models\BloodPack::class,
  'with' => ['incomingBloods'],
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
  'with' => ['storages'],
 ],
 'user' => [
  'view' => 'pages.master.user.index',
  'model' => App\Models\User::class,
  'with' => ['roles'],
 ],
 'vendor' => [
  'view' => 'pages.master.vendor.index',
  'model' => App\Models\Vendor::class,
 ],
];
