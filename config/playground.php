<?php

return [
 // TESTING
 'print-test' => [
  'label' => 'Print',
  'view' => 'pages.playground.print-test.index',
  'route' => 'playground.print.index',
  'section' => 'testing',
  'group' => '',
 ],
 // FIXING
 'crossmatch-result' => [
  'label' => 'Hasil Crossmatch',
  'view' => 'pages.playground.crossmatch-result.index',
  'route' => 'playground.fixing.crossmatch-result.index',
  'section' => 'fixing',
  'group' => '',
 ],
 'blood-stock-data' => [
  'label' => 'Data Kantong Darah',
  'view' => 'pages.playground.blood-stock-data.index',
  'route' => 'playground.fixing.blood-stock-data.index',
  'section' => 'fixing',
  'group' => '',
 ],
 // SETTING
 'blood-component' => [
  'label' => 'Komponen Darah',
  'view' => 'pages.playground.config.blood-component.index',
  'route' => 'playground.setting.config.blood-component.index',
  'section' => 'setting',
  'group' => 'config',
 ],
];
