<table>
 <thead>
  <tr>
   <th>No</th>
   <th>Bag Number</th>
   <th>Blood Groups</th>
   <th>Volume</th>
   <th>Aftap</th>
   <th>Process</th>
   <th>Expiry</th>
   <th>Status</th>
   <th>Used At</th>
   <th>Ready At</th>
  </tr>
 </thead>
 <tbody>
  @foreach ($bloodStocks as $index => $bloodStock)
  @php
  $bloodPack = $bloodStock->bloodPacks;
  $bloodGroup = $bloodPack->blood_group->value . $bloodPack->blood_rhesus . " " . $bloodPack->blood_component->value;

  $hiv = $bloodStock->is_hiv ? 'R' : 'NR';
  $hbsag = $bloodStock->is_hbsag ? 'R' : 'NR';
  $hcv = $bloodStock->is_hcv ? 'R' : 'NR';
  $syphilis = $bloodStock->is_syphilis ? 'R' : 'NR';

  $bloodStatus = $bloodStock->blood_status;
  $status = $bloodStock->deleted_at ? 'Trashed' : \Illuminate\Support\Str::headline($bloodStatus ?? '-');
  @endphp
  <tr>
   <td>{{ $index + 1 }}</td>
   <td>{{ $bloodStock->bag_number }}</td>
   <td>{{ $bloodGroup ?: '-' }}</td>
   <td>{{ $bloodStock->blood_volume }}</td>
   <td>{{ $bloodStock->aftap_date ?? '-' }}</td>
   <td>{{ $bloodStock->process_date ?? '-' }}</td>
   <td>{{ $bloodStock->expiry_date ?? '-' }}</td>
   <td>{{ $status }}</td>
   <td>{{ $bloodStock->used_at ?? '-' }}</td>
   <td>{{ $bloodStock->created_at?->format('d-m-Y H:i') ?? '-' }}</td>
  </tr>
  @endforeach
 </tbody>
</table>