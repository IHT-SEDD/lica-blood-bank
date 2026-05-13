<table>
 <thead>
  <tr>
   <th>No</th>
   <th>PO Number</th>
   <th>Batch Number</th>
   <th>Vendor</th>
   <th>Total Qty</th>
   <th>Blood Groups</th>
   <th>Status</th>
   <th>Registered At</th>
   <th>Ready At</th>
  </tr>
 </thead>
 <tbody>
  @foreach ($incomings as $index => $incoming)
  @php
  $bloodGroups = $incoming->incomingBloodDetails
  ->map(function ($detail) {
  $pack = $detail->bloodPacks;
  if (!$pack) return null;
  return "{$pack->blood_group->value}{$pack->blood_rhesus} {$pack->blood_component->value}";
  })
  ->filter()
  ->implode(', ');

  $status = $incoming->deleted_at ? 'Trashed' : \Illuminate\Support\Str::headline($incoming->status->value ?? '-');
  $vendor = $incoming->orderBloods?->vendors?->name;
  @endphp
  <tr>
   <td>{{ $index + 1 }}</td>
   <td>{{ $incoming->po_number }}</td>
   <td>{{ $incoming->batch_number }}</td>
   <td>{{ $vendor ?? '-' }}</td>
   <td>{{ $incoming->total_blood_data }} Bags</td>
   <td>{{ $bloodGroups ?: '-' }}</td>
   <td>{{ $status }}</td>
   <td>{{ $incoming->created_at?->format('d-m-Y H:i') ?? '-' }}</td>
   <td>{{ $incoming->stock_ready_at ?? '-' }}</td>
  </tr>
  @endforeach
 </tbody>
</table>