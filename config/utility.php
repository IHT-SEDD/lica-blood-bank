<?php

return [
 'user' => [
  'model' => App\Models\User::class,
  'label' => 'name',
  'with' => ['roles'],
 ],
 'role' => [
  'model' => Spatie\Permission\Models\Role::class,
  'label' => 'name',
  'with'  => [],
 ],
 'vendor' => [
  'model' => App\Models\Vendor::class,
  'label' => 'name',
  'with'  => [],
 ],
 'storage' => [
  'model' => App\Models\Storage::class,
  'label' => 'name',
  'with'  => [],
 ],
 'storage-rack' => [
  'model' => App\Models\StorageRack::class,
  'label' => 'name',
  'with'  => ['storages'],
 ],
 'blood-pack' => [
  'model' => '',
  'label' => 'initial',
  'with'  => [],
 ],
 'purchase-order' => [
  'type' => 'static',
  'model' => App\Models\OrderBlood::class,
  'label' => 'po_number',
  'with' => [],
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
 'order-status' => [
  'type' => 'enum',
 ],
 'blood-status' => [
  'type' => 'enum',
 ],
 'add-incoming-stock-method' => [
  'type' => 'enum',
 ],
 'incoming-stock-status' => [
  'type' => 'enum',
 ],
];
