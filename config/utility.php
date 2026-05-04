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
  'model' => App\Models\BloodPack::class,
  'label' => 'label',
  'with' => [],
 ],
 'purchase-order' => [
  'type' => 'static',
  'model' => App\Models\OrderBlood::class,
  'label' => 'po_number',
  'with' => [],
 ],
 'patient' => [
  'model' => App\Models\Patient::class,
  'label' => 'name',
  'with' => [],
 ],
 'room' => [
  'model' => '',
  'label' => 'name',
  'with' => [],
 ],
 'insurance' => [
  'model' => App\Models\Insurance::class,
  'label' => 'name',
  'with' => [],
 ],
 'doctor' => [
  'model' => App\Models\Doctor::class,
  'label' => 'name',
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
 'relation-type' => [
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
