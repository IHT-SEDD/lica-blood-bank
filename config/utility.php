<?php

return [
 'user' => [
  'model' => App\Models\User::class,
  'with' => ['roles'],
  'label' => 'name',
 ],
 'role' => [
  'model' => Spatie\Permission\Models\Role::class,
  'label' => 'name',
 ],
 'vendor' => [
  'model' => App\Models\Vendor::class,
  'label' => 'name',
 ],
 'storage' => [
  'model' => App\Models\Storage::class,
  'label' => 'name',
 ],
 'storage-rack' => [
  'model' => App\Models\StorageRack::class,
  'label' => 'name',
 ],
 'blood-pack' => [
  'model' => '',
  'label' => 'initial',
 ],
 'blood-group' => [
  'type' => 'enum',
 ],
 'blood-component' => [
  'type' => 'enum',
 ],
 'blood-rhesus' => [
  'type' => 'enum',
 ],
];
