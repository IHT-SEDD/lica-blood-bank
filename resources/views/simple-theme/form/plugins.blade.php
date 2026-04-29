@extends('layouts.vertical', ['title' => 'Plugins'])

@section('styles')
@endsection

@section('content')
@include('layouts.shared.page-title', [
'title' => 'Form Plugins',
'subTitle' =>
'Enhance your forms with powerful plugins like Flatpickr, Choices.js, Typeahead, and Input Touchspin for better
interactivity.',
'badgeIcon' => 'puzzle',
'badgeTitle' => 'Enhanced Inputs',
])


<div class="row">
    <div cl="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Flatpickr</h5>
            </div>
            <div class="card-body rounded-bottom-0">
                <p class="text-muted mb-2">
                    Lightweight, powerful javascript datetimepicker with no dependencies
                </p>
                <a class="btn btn-link shadow-none p-0 fw-medium" href="https://flatpickr.js.org/" target="_blank">
                    View Official Website
                    <i class="ti ti-chevron-right ms-1"></i>
                </a>
            </div>
            <div class="card-body border-top-0 rounded-0">
                <!-- Basic -->
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h5>Basic</h5>
                        <p class="text-muted mb-0">Set <code>data-provider="flatpickr"
                                data-date-format="d M, Y"</code>.</p>
                    </div>
                    <div class="col-lg-6">
                        <input class="form-control" data-date-format="d M, Y" data-provider="flatpickr" type="text" />
                    </div>
                </div>
                <div class="my-4 border-top border-dashed"></div>
                <!-- DateTime -->
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h5>DateTime</h5>
                        <p class="text-muted mb-0">Set <code>data-provider="flatpickr"
                                data-date-format="d.m.y" data-enable-time</code>.</p>
                    </div>
                    <div class="col-lg-6">
                        <input class="form-control" data-date-format="d.m.y" data-enable-time=""
                            data-provider="flatpickr" type="text" />
                    </div>
                </div>
                <div class="my-4 border-top border-dashed"></div>
                <!-- Human-Friendly Dates -->
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h5>Human-Friendly Dates</h5>
                        <p class="text-muted mb-0">Set <code>data-provider="flatpickr"
                                data-altFormat="F j, Y"</code>.</p>
                    </div>
                    <div class="col-lg-6">
                        <input class="form-control flatpickr-input" data-altformat="F j, Y" data-provider="flatpickr"
                            type="text" />
                    </div>
                </div>
                <div class="my-4 border-top border-dashed"></div>
                <!-- MinDate and MaxDate -->
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h5>MinDate and MaxDate</h5>
                        <p class="text-muted mb-0">Set <code>data-provider="flatpickr"
                                data-date-format="d M, Y" data-minDate="..." data-maxDate="..."</code>.</p>
                    </div>
                    <div class="col-lg-6">
                        <input class="form-control" data-date-format="d M, Y" data-maxdate="29 12,2021"
                            data-mindate="25 12, 2021" data-provider="flatpickr" placeholder="Select Date"
                            type="text" />
                    </div>
                </div>
                <div class="my-4 border-top border-dashed"></div>
                <!-- Default Date -->
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h5>Default Date</h5>
                        <p class="text-muted mb-0">Set <code>data-provider="flatpickr"
                                data-date-format="d M, Y" data-default-date="..."</code>.</p>
                    </div>
                    <div class="col-lg-6">
                        <input class="form-control" data-date-format="d M, Y" data-default-date="25 12,2021"
                            data-provider="flatpickr" type="text" />
                    </div>
                </div>
                <div class="my-4 border-top border-dashed"></div>
                <!-- Disabling Dates -->
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h5>Disabling Dates</h5>
                        <p class="text-muted mb-0">Set <code>data-provider="flatpickr"
                                data-disable-date="..."</code>.</p>
                    </div>
                    <div class="col-lg-6">
                        <input class="form-control" data-date-format="d M, Y" data-disable-date="15 12,2021"
                            data-provider="flatpickr" type="text" />
                    </div>
                </div>
                <div class="my-4 border-top border-dashed"></div>
                <!-- Selecting Multiple Dates -->
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h5>Selecting Multiple Dates</h5>
                        <p class="text-muted mb-0">Set <code>data-provider="flatpickr"
                                data-multiple-date="true"</code>.</p>
                    </div>
                    <div class="col-lg-6">
                        <input class="form-control" data-date-format="d M, Y" data-multiple-date="true"
                            data-provider="flatpickr" type="text" />
                    </div>
                </div>
                <div class="my-4 border-top border-dashed"></div>
                <!-- Range -->
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h5>Range</h5>
                        <p class="text-muted mb-0">Set <code>data-provider="flatpickr"
                                data-range-date="true"</code>.</p>
                    </div>
                    <div class="col-lg-6">
                        <input class="form-control" data-date-format="d M, Y" data-provider="flatpickr"
                            data-range-date="true" type="text" />
                    </div>
                </div>
                <div class="my-4 border-top border-dashed"></div>
                <!-- Week Numbers -->
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h5>Week Numbers</h5>
                        <p class="text-muted mb-0">Set <code>data-provider="flatpickr"
                                data-week-number</code>.</p>
                    </div>
                    <div class="col-lg-6">
                        <input class="form-control" data-date-format="d M, Y" data-provider="flatpickr"
                            data-week-number="" type="text" />
                    </div>
                </div>
                <div class="my-4 border-top border-dashed"></div>
                <!-- Inline -->
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h5>Inline</h5>
                        <p class="text-muted mb-0">Set <code>data-provider="flatpickr"
                                data-inline-date="true"</code>.</p>
                    </div>
                    <div class="col-lg-6">
                        <input class="form-control" data-date-format="d M, Y" data-default-date="25 01,2021"
                            data-inline-date="true" data-provider="flatpickr" type="text" />
                    </div>
                </div>
            </div><!-- end card-body -->
            <div class="card-body rounded-top-0 border-top-0">
                <h4 class="card-title fs-sm fw-bold mb-4">Timepicker</h4>
                <!-- Timepicker -->
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h5>Timepicker</h5>
                        <p class="text-muted mb-0">Set <code>data-provider="timepickr"
                                data-time-basic="true"</code> attribute.</p>
                    </div>
                    <div class="col-lg-6">
                        <input class="form-control" data-provider="timepickr" data-time-basic="true"
                            id="timepicker-example" placeholder="Select Time" type="text" />
                    </div>
                </div>
                <div class="my-4 border-top border-dashed"></div>
                <!-- 24-hour Time Picker -->
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h5>24-hour Time Picker</h5>
                        <p class="text-muted mb-0">Set <code>data-provider="timepickr"
                                data-time-hrs="true"</code> attribute.</p>
                    </div>
                    <div class="col-lg-6">
                        <input class="form-control" data-provider="timepickr" data-time-hrs="true" id="timepicker-24hrs"
                            placeholder="Select Time" type="text" />
                    </div>
                </div>
                <div class="my-4 border-top border-dashed"></div>
                <!-- Time Picker w/ Limits -->
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h5>Time Picker w/ Limits</h5>
                        <p class="text-muted mb-0">Set <code>data-provider="timepickr"
                                data-min-time="Min.Time" data-max-time="Max.Time"</code> attribute.</p>
                    </div>
                    <div class="col-lg-6">
                        <input class="form-control" data-max-time="16:00" data-min-time="13:00"
                            data-provider="timepickr" placeholder="Select Time" type="text" />
                    </div>
                </div>
                <div class="my-4 border-top border-dashed"></div>
                <!-- Preloading Time -->
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h5>Preloading Time</h5>
                        <p class="text-muted mb-0">Set <code>data-provider="timepickr"
                                data-default-time="Your Default Time"</code> attribute.</p>
                    </div>
                    <div class="col-lg-6">
                        <input class="form-control" data-default-time="16:45" data-provider="timepickr" type="text" />
                    </div>
                </div>
                <div class="my-4 border-top border-dashed"></div>
                <!-- Inline -->
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h5>Inline</h5>
                        <p class="text-muted mb-0">Set <code>data-provider="timepickr"
                                data-time-inline="Your Default Time"</code> attribute.</p>
                    </div>
                    <div class="col-lg-6">
                        <input class="form-control" data-provider="timepickr" data-time-inline="11:42" type="text" />
                    </div>
                </div>
            </div><!-- end card-body -->
        </div><!-- end card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Choices.Js</h5>
            </div>
            <div class="card-body rounded-bottom-0">
                <p class="text-muted mb-2">
                    Choices.js is a lightweight, configurable select box/text input plugin. Similar to
                    Select2 and Selectize but without the jQuery dependency.
                </p>
                <a class="btn btn-link shadow-none p-0 fw-medium" href="https://choices-js.github.io/Choices/"
                    target="_blank">
                    View Official Website
                    <i class="ti ti-chevron-right ms-1"></i>
                </a>
            </div>
            <div class="card-body border-top-0 rounded-top-0">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h5>Single Select Input: Default</h5>
                        <p class="text-muted mb-0">Set <code>data-choices</code> attribute to set a default
                            single select.</p>
                    </div>
                    <div class="col-lg-6">
                        <select class="form-control" data-choices="" id="choices-single-default"
                            name="choices-single-default">
                            <option value="">This is a placeholder</option>
                            <option value="Choice 1">Choice 1</option>
                            <option value="Choice 2">Choice 2</option>
                            <option value="Choice 3">Choice 3</option>
                        </select>
                    </div>
                </div>
                <div class="my-4 border-top border-dashed"></div>
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h5>Single Select Input: Option Groups</h5>
                        <p class="text-muted mb-0">Set <code>data-choices data-choices-groups</code>
                            attribute to set option group</p>
                    </div>
                    <div class="col-lg-6">
                        <select class="form-control" data-choices="" data-choices-groups=""
                            data-placeholder="Select City" id="choices-single-groups" name="choices-single-groups">
                            <option value="">Choose a city</option>
                            <optgroup label="UK">
                                <option value="London">London</option>
                                <option value="Manchester">Manchester</option>
                                <option value="Liverpool">Liverpool</option>
                            </optgroup>
                            <optgroup label="FR">
                                <option value="Paris">Paris</option>
                                <option value="Lyon">Lyon</option>
                                <option value="Marseille">Marseille</option>
                            </optgroup>
                            <optgroup disabled="" label="DE">
                                <option value="Hamburg">Hamburg</option>
                                <option value="Munich">Munich</option>
                                <option value="Berlin">Berlin</option>
                            </optgroup>
                            <optgroup label="US">
                                <option value="New York">New York</option>
                                <option disabled="" value="Washington">Washington</option>
                                <option value="Michigan">Michigan</option>
                            </optgroup>
                            <optgroup label="SP">
                                <option value="Madrid">Madrid</option>
                                <option value="Barcelona">Barcelona</option>
                                <option value="Malaga">Malaga</option>
                            </optgroup>
                            <optgroup label="CA">
                                <option value="Montreal">Montreal</option>
                                <option value="Toronto">Toronto</option>
                                <option value="Vancouver">Vancouver</option>
                            </optgroup>
                        </select>
                    </div>
                </div>
                <div class="my-4 border-top border-dashed"></div>
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h5>Single Select Input: No Search</h5>
                        <p class="text-muted mb-0">Set <code>data-choices data-choices-search-false
                                    data-choices-removeItem</code></p>
                    </div>
                    <div class="col-lg-6">
                        <select class="form-control" data-choices="" data-choices-removeitem=""
                            data-choices-search-false="" id="choices-single-no-search" name="choices-single-no-search">
                            <option value="Zero">Zero</option>
                            <option value="One">One</option>
                            <option value="Two">Two</option>
                            <option value="Three">Three</option>
                            <option value="Four">Four</option>
                            <option value="Five">Five</option>
                            <option value="Six">Six</option>
                        </select>
                    </div>
                </div>
                <div class="my-4 border-top border-dashed"></div>
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h5>Single Select Input: No Sorting</h5>
                        <p class="text-muted mb-0">Set <code>data-choices data-choices-sorting-false</code>
                            attribute.</p>
                    </div>
                    <div class="col-lg-6">
                        <select class="form-control" data-choices="" data-choices-sorting-false=""
                            id="choices-single-no-sorting" name="choices-single-no-sorting">
                            <option value="Madrid">Madrid</option>
                            <option value="Toronto">Toronto</option>
                            <option value="Vancouver">Vancouver</option>
                            <option value="London">London</option>
                            <option value="Manchester">Manchester</option>
                            <option value="Liverpool">Liverpool</option>
                            <option value="Paris">Paris</option>
                            <option value="Malaga">Malaga</option>
                            <option disabled="" value="Washington">Washington</option>
                            <option value="Lyon">Lyon</option>
                            <option value="Marseille">Marseille</option>
                            <option value="Hamburg">Hamburg</option>
                            <option value="Munich">Munich</option>
                            <option value="Barcelona">Barcelona</option>
                            <option value="Berlin">Berlin</option>
                            <option value="Montreal">Montreal</option>
                            <option value="New York">New York</option>
                            <option value="Michigan">Michigan</option>
                        </select>
                    </div>
                </div>
                <div class="my-4 border-top border-dashed"></div>
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h5>Multiple Select Input: Default</h5>
                        <p class="text-muted mb-0">Set <code>data-choices multiple</code> attribute.</p>
                    </div>
                    <div class="col-lg-6">
                        <select class="form-control" data-choices="" id="choices-multiple-default" multiple=""
                            name="choices-multiple-default">
                            <option selected="" value="Choice 1">Choice 1</option>
                            <option value="Choice 2">Choice 2</option>
                            <option value="Choice 3">Choice 3</option>
                            <option disabled="" value="Choice 4">Choice 4</option>
                        </select>
                    </div>
                </div>
                <div class="my-4 border-top border-dashed"></div>
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h5>Multiple Select Input: With Remove Button</h5>
                        <p class="text-muted mb-0">Set <code>data-choices data-choices-removeItem
                                    multiple</code> attribute.</p>
                    </div>
                    <div class="col-lg-6">
                        <select class="form-control" data-choices="" data-choices-removeitem=""
                            id="choices-multiple-remove-button" multiple="" name="choices-multiple-remove-button">
                            <option selected="" value="Choice 1">Choice 1</option>
                            <option value="Choice 2">Choice 2</option>
                            <option value="Choice 3">Choice 3</option>
                            <option value="Choice 4">Choice 4</option>
                        </select>
                    </div>
                </div>
                <div class="my-4 border-top border-dashed"></div>
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h5>Multiple Select Input: Option Groups</h5>
                        <p class="text-muted mb-0">Set <code>data-choices
                                    data-choices-multiple-groups="true" multiple</code> attribute.</p>
                    </div>
                    <div class="col-lg-6">
                        <select class="form-control" data-choices="" data-choices-multiple-groups="true"
                            id="choices-multiple-groups" multiple="" name="choices-multiple-groups">
                            <option value="">Choose a city</option>
                            <optgroup label="UK">
                                <option value="London">London</option>
                                <option value="Manchester">Manchester</option>
                                <option value="Liverpool">Liverpool</option>
                            </optgroup>
                            <optgroup label="FR">
                                <option value="Paris">Paris</option>
                                <option value="Lyon">Lyon</option>
                                <option value="Marseille">Marseille</option>
                            </optgroup>
                            <optgroup disabled="" label="DE">
                                <option value="Hamburg">Hamburg</option>
                                <option value="Munich">Munich</option>
                                <option value="Berlin">Berlin</option>
                            </optgroup>
                            <optgroup label="US">
                                <option value="New York">New York</option>
                                <option disabled="" value="Washington">Washington</option>
                                <option value="Michigan">Michigan</option>
                            </optgroup>
                            <optgroup label="SP">
                                <option value="Madrid">Madrid</option>
                                <option value="Barcelona">Barcelona</option>
                                <option value="Malaga">Malaga</option>
                            </optgroup>
                            <optgroup label="CA">
                                <option value="Montreal">Montreal</option>
                                <option value="Toronto">Toronto</option>
                                <option value="Vancouver">Vancouver</option>
                            </optgroup>
                        </select>
                    </div>
                </div>
                <div class="my-4 border-top border-dashed"></div>
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h5>Text Input: Limit Values with Remove Button</h5>
                        <p class="text-muted mb-0">Set <code>data-choices data-choices-limit="3"
                                    data-choices-removeItem</code> attribute.</p>
                    </div>
                    <div class="col-lg-6">
                        <input class="form-control" data-choices="" data-choices-limit="3" data-choices-removeitem=""
                            id="choices-text-remove-button" type="text" value="Task-1" />
                    </div>
                </div>
                <div class="my-4 border-top border-dashed"></div>
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h5>Text Input: Unique Values Only</h5>
                        <p class="text-muted mb-0">Set <code>data-choices
                                    data-choices-text-unique-true</code> attribute.</p>
                    </div>
                    <div class="col-lg-6">
                        <input class="form-control" data-choices="" data-choices-text-unique-true=""
                            id="choices-text-unique-values" type="text" value="Project-A, Project-B" />
                    </div>
                </div>
                <div class="my-4 border-top border-dashed"></div>
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h5>Text Input: Disabled</h5>
                        <p class="text-muted mb-0">Set <code>data-choices
                                    data-choices-text-disabled-true</code> attribute.</p>
                    </div>
                    <div class="col-lg-6">
                        <input class="form-control" data-choices="" data-choices-text-disabled-true=""
                            id="choices-text-disabled" type="text" value="josh@joshuajohnson.co.uk, joe@bloggs.co.uk" />
                    </div>
                </div>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Typeahead</h5>
            </div>
            <div class="card-body rounded-bottom-0">
                <p class="text-muted mb-2">
                    A flexible JavaScript library that provides a strong foundation for building robust
                    typeaheads
                </p>
                <a class="btn btn-link shadow-none p-0 fw-semibold" href="https://twitter.github.io/typeahead.js/"
                    target="_blank">
                    View Official Website
                    <i class="ti ti-chevron-right ms-1"></i>
                </a>
            </div>
            <div class="card-body rounded-top-0 border-top-0">
                <!-- Basic -->
                <div class="row g-3">
                    <div class="col-lg-6">
                        <h5 class="mb-1">Basic</h5>
                    </div>
                    <div class="col-lg-6">
                        <input autocomplete="off" class="form-control typeahead" placeholder="Enter states from USA"
                            type="text" />
                    </div>
                </div>
                <div class="my-4 border-top border-dashed"></div>
                <!-- Bloodhound -->
                <div class="row g-3">
                    <div class="col-lg-6">
                        <h5 class="mb-1">BloodHound (Suggestion Engine)</h5>
                    </div>
                    <div class="col-lg-6">
                        <input autocomplete="off" class="form-control bloodhound-typeahead"
                            placeholder="Enter states from USA" type="text" />
                    </div>
                </div>
                <div class="my-4 border-top border-dashed"></div>
                <!-- Prefetch -->
                <div class="row g-3">
                    <div class="col-lg-6">
                        <h5 class="mb-1">Prefetch</h5>
                    </div>
                    <div class="col-lg-6">
                        <input autocomplete="off" class="form-control prefetch-typeahead"
                            placeholder="Enter states from USA" type="text" />
                    </div>
                </div>
                <div class="my-4 border-top border-dashed"></div>
                <!-- Default Suggestions -->
                <div class="row g-3">
                    <div class="col-lg-6">
                        <h5 class="mb-1">Default Suggestions</h5>
                    </div>
                    <div class="col-lg-6">
                        <input autocomplete="off" class="form-control default-suggestions-typeahead"
                            placeholder="Default Suggestions" type="text" />
                    </div>
                </div>
                <div class="my-4 border-top border-dashed"></div>
                <!-- Custom Template -->
                <div class="row g-3">
                    <div class="col-lg-6">
                        <h5 class="mb-1">Custom Template</h5>
                    </div>
                    <div class="col-lg-6">
                        <input autocomplete="off" class="form-control custom-template-typeahead"
                            placeholder="Search For Oscar Winner" type="text" />
                    </div>
                </div>
                <div class="my-4 border-top border-dashed"></div>
                <!-- Multiple Datasets -->
                <div class="row g-3">
                    <div class="col-lg-6">
                        <h5 class="mb-1">Multiple Datasets</h5>
                    </div>
                    <div class="col-lg-6">
                        <input autocomplete="off" class="form-control multi-datasets-typeahead"
                            placeholder="NBA and NHL Teams" type="text" />
                    </div>
                </div>
            </div> <!-- end card-body -->
        </div> <!-- end card-->
        <div class="card">
            <div class="card-header">
                <div class="flex-grow-1">
                    <h5 class="card-title">Input Touchspin</h5>
                </div>
                <span class="badge badge-soft-success badge-label py-1 fs-xxs">Exclusive</span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-lg-6">
                        <h5 class="mb-1">Default Touchspin</h5>
                    </div>
                    <div class="col-lg-6">
                        <div class="input-group" data-touchspin="">
                            <button class="btn btn-light floating" data-minus="" type="button"><i
                                    class="ti ti-minus"></i></button>
                            <input class="form-control form-control-sm border-0" max="800000" type="number" value="1" />
                            <button class="btn btn-light floating" data-plus="" type="button"><i
                                    class="ti ti-plus"></i></button>
                        </div>
                    </div>
                </div>
                <div class="my-4 border-top border-dashed"></div>
                <div class="row g-3">
                    <div class="col-lg-6">
                        <h5 class="mb-1">Sizes</h5>
                    </div>
                    <div class="col-lg-6">
                        <div class="input-group input-group-sm" data-touchspin="">
                            <button class="btn btn-light floating" data-minus="" type="button"><i
                                    class="ti ti-minus"></i></button>
                            <input class="form-control form-control border-0" max="800000" type="number" value="0" />
                            <button class="btn btn-light floating" data-plus="" type="button"><i
                                    class="ti ti-plus"></i></button>
                        </div>
                        <div class="input-group input-group-lg mt-2" data-touchspin="">
                            <button class="btn btn-light floating" data-minus="" type="button"><i
                                    class="ti ti-minus"></i></button>
                            <input class="form-control form-control border-0" max="800000" type="number" value="0" />
                            <button class="btn btn-light floating" data-plus="" type="button"><i
                                    class="ti ti-plus"></i></button>
                        </div>
                    </div>
                </div>
                <div class="my-4 border-top border-dashed"></div>
                <div class="row g-3">
                    <div class="col-lg-6">
                        <h5 class="mb-1">Colors</h5>
                    </div>
                    <div class="col-lg-6">
                        <div class="input-group" data-touchspin="">
                            <button class="btn btn-primary floating" data-minus="" type="button"><i
                                    class="ti ti-minus"></i></button>
                            <input class="form-control form-control-sm border-0" max="800000" type="number"
                                value="100" />
                            <button class="btn btn-primary floating" data-plus="" type="button"><i
                                    class="ti ti-plus"></i></button>
                        </div>
                        <div class="input-group mt-2" data-touchspin="">
                            <button class="btn btn-secondary floating" data-minus="" type="button"><i
                                    class="ti ti-minus"></i></button>
                            <input class="form-control form-control-sm border-0" max="800000" type="number"
                                value="100" />
                            <button class="btn btn-secondary floating" data-plus="" type="button"><i
                                    class="ti ti-plus"></i></button>
                        </div>
                        <div class="input-group mt-2" data-touchspin="">
                            <button class="btn btn-info floating" data-minus="" type="button"><i
                                    class="ti ti-minus"></i></button>
                            <input class="form-control form-control-sm border-0" max="800000" type="number"
                                value="100" />
                            <button class="btn btn-info floating" data-plus="" type="button"><i
                                    class="ti ti-plus"></i></button>
                        </div>
                        <div class="input-group mt-2" data-touchspin="">
                            <button class="btn btn-success floating" data-minus="" type="button"><i
                                    class="ti ti-minus"></i></button>
                            <input class="form-control form-control-sm border-0" max="800000" type="number"
                                value="100" />
                            <button class="btn btn-success floating" data-plus="" type="button"><i
                                    class="ti ti-plus"></i></button>
                        </div>
                        <div class="input-group mt-2" data-touchspin="">
                            <button class="btn btn-warning floating" data-minus="" type="button"><i
                                    class="ti ti-minus"></i></button>
                            <input class="form-control form-control-sm border-0" max="800000" type="number"
                                value="100" />
                            <button class="btn btn-warning floating" data-plus="" type="button"><i
                                    class="ti ti-plus"></i></button>
                        </div>
                        <div class="input-group mt-2" data-touchspin="">
                            <button class="btn btn-danger floating" data-minus="" type="button"><i
                                    class="ti ti-minus"></i></button>
                            <input class="form-control form-control-sm border-0" max="800000" type="number"
                                value="100" />
                            <button class="btn btn-danger floating" data-plus="" type="button"><i
                                    class="ti ti-plus"></i></button>
                        </div>
                        <div class="input-group mt-2" data-touchspin="">
                            <button class="btn btn-dark floating" data-minus="" type="button"><i
                                    class="ti ti-minus"></i></button>
                            <input class="form-control form-control-sm border-0" max="800000" type="number"
                                value="100" />
                            <button class="btn btn-dark floating" data-plus="" type="button"><i
                                    class="ti ti-plus"></i></button>
                        </div>
                        <div class="input-group mt-2" data-touchspin="">
                            <button class="btn btn-primary floating" data-minus="" type="button"><i
                                    class="ti ti-minus"></i></button>
                            <input class="form-control form-control-sm border-0" max="800000" type="number"
                                value="100" />
                            <button class="btn btn-primary floating" data-plus="" type="button"><i
                                    class="ti ti-plus"></i></button>
                        </div>
                        <div class="input-group mt-2" data-touchspin="">
                            <button class="btn btn-soft-primary floating" data-minus="" type="button"><i
                                    class="ti ti-minus"></i></button>
                            <input class="form-control form-control-sm border-0" max="800000" type="number"
                                value="100" />
                            <button class="btn btn-soft-primary floating" data-plus="" type="button"><i
                                    class="ti ti-plus"></i></button>
                        </div>
                    </div>
                </div>
                <div class="my-4 border-top border-dashed"></div>
                <div class="row g-3">
                    <div class="col-lg-6">
                        <h5 class="mb-1">Readonly</h5>
                    </div>
                    <div class="col-lg-6">
                        <div class="input-group" data-touchspin="">
                            <button class="btn btn-light floating" data-minus="" type="button"><i
                                    class="ti ti-minus"></i></button>
                            <input class="form-control form-control-sm border-0" max="800000" readonly="" type="number"
                                value="1" />
                            <button class="btn btn-light floating" data-plus="" type="button"><i
                                    class="ti ti-plus"></i></button>
                        </div>
                    </div>
                </div>
                <div class="my-4 border-top border-dashed"></div>
                <div class="row g-3">
                    <div class="col-lg-6">
                        <h5 class="mb-1">Disabled</h5>
                    </div>
                    <div class="col-lg-6">
                        <div class="input-group" data-touchspin="">
                            <button class="btn btn-light floating" data-minus="" disabled="" type="button"><i
                                    class="ti ti-minus"></i></button>
                            <input class="form-control form-control-sm border-0" disabled="" max="800000" type="number"
                                value="1" />
                            <button class="btn btn-light floating" data-plus="" disabled="" type="button"><i
                                    class="ti ti-plus"></i></button>
                        </div>
                    </div>
                </div>
                <div class="my-4 border-top border-dashed"></div>
                <div class="row g-3">
                    <div class="col-lg-6">
                        <h5 class="mb-1">Style</h5>
                    </div>
                    <div class="col-lg-6">
                        <div class="input-group" data-touchspin="">
                            <button class="btn btn-primary rounded-circle floating" data-minus="" type="button"><i
                                    class="ti ti-minus"></i></button>
                            <input class="form-control form-control-sm border-0" max="800000" type="number"
                                value="100" />
                            <button class="btn btn-primary rounded-circle floating" data-plus="" type="button"><i
                                    class="ti ti-plus"></i></button>
                        </div>
                        <div class="input-group rounded-pill mt-2" data-touchspin="">
                            <button class="btn btn-primary rounded-circle floating" data-minus="" type="button"><i
                                    class="ti ti-minus"></i></button>
                            <input class="form-control form-control-sm border-0" max="800000" type="number"
                                value="100" />
                            <button class="btn btn-primary rounded-circle floating" data-plus="" type="button"><i
                                    class="ti ti-plus"></i></button>
                        </div>
                        <div class="input-group border-0 mt-2" data-touchspin="">
                            <button class="btn btn-outline-secondary" data-minus="" type="button"><i
                                    class="ti ti-minus"></i></button>
                            <input class="form-control border-secondary" max="100" min="0" type="number" value="2" />
                            <button class="btn btn-outline-secondary" data-plus="" type="button"><i
                                    class="ti ti-plus"></i></button>
                        </div>
                        <div class="input-group border-0 mt-2" data-touchspin="">
                            <button class="btn btn-soft-success" data-minus="" type="button"><i
                                    class="ti ti-minus"></i></button>
                            <input class="form-control border-success-subtle" max="100" min="0" type="number"
                                value="2" />
                            <button class="btn btn-soft-success" data-plus="" type="button"><i
                                    class="ti ti-plus"></i></button>
                        </div>
                    </div>
                </div>
                <div class="my-4 border-top border-dashed"></div>
                <div class="row g-3">
                    <div class="col-lg-6">
                        <h5 class="mb-1">Vertical Style</h5>
                    </div>
                    <div class="col-lg-6">
                        <div class="input-group input-group-sm" data-touchspin="">
                            <div class="btn-group-vertical">
                                <button class="btn btn-soft-success" data-plus="" type="button"><i
                                        class="ti ti-plus"></i></button>
                                <button class="btn btn-soft-danger" data-minus="" type="button"><i
                                        class="ti ti-minus"></i></button>
                            </div>
                            <input class="form-control border-0" max="10" min="0" type="number" value="1" />
                        </div>
                        <div class="input-group mt-2" data-touchspin="">
                            <div class="btn-group-vertical">
                                <button class="btn btn-success" data-plus="" type="button"><i
                                        class="ti ti-plus"></i></button>
                                <button class="btn btn-danger" data-minus="" type="button"><i
                                        class="ti ti-minus"></i></button>
                            </div>
                            <input class="form-control border-0" max="10" min="0" type="number" value="1" />
                        </div>
                        <div class="input-group input-group-lg mt-2" data-touchspin="">
                            <div class="btn-group-vertical">
                                <button class="btn btn-dark" data-plus="" type="button"><i
                                        class="ti ti-plus"></i></button>
                                <button class="btn btn-dark" data-minus="" type="button"><i
                                        class="ti ti-minus"></i></button>
                            </div>
                            <input class="form-control border-0" max="10" min="0" type="number" value="1" />
                        </div>
                        <div class="input-group mt-2" data-touchspin="">
                            <input class="form-control border-0" max="10" min="0" type="number" value="1" />
                            <div class="btn-group-vertical">
                                <button class="btn btn-dark" data-plus="" type="button"><i
                                        class="ti ti-plus"></i></button>
                                <button class="btn btn-dark" data-minus="" type="button"><i
                                        class="ti ti-minus"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end card-body -->
        </div> <!-- end card-->
    </div> <!-- end col -->
</div> <!-- end row -->
@endsection

@section('scripts')
@vite(['resources/js/pages/form-choice.js', 'resources/js/pages/form-typehead.js'])
@endsection