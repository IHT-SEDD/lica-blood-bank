<table>
 <thead>
  <tr>
   <th>No</th>
   <th>PO Number</th>
   <th>Vendor</th>
   <th>Total Qty</th>
   <th>Blood Groups</th>
   <th>Status</th>
   <th>Order At</th>
  </tr>
 </thead>
 <tbody>
  @foreach ($orders as $index => $order)
  @php
  $bloodGroups = $order->orderBloodDetails
  ->map(function ($detail) {
  $pack = $detail->bloodPacks;
  if (!$pack) return null;
  return "{$pack->blood_group->value}{$pack->blood_rhesus} {$pack->blood_component->value}";
  })
  ->filter()
  ->implode(', ');

  $status = $order->deleted_at ? 'Trashed' : \Illuminate\Support\Str::headline($order->status->value ?? '-');
  @endphp
  <tr>
   <td>{{ $index + 1 }}</td>
   <td>{{ $order->po_number }}</td>
   <td>{{ $order->vendors?->name ?? '-' }}</td>
   <td>{{ $order->total_quantity }} Bags</td>
   <td>{{ $bloodGroups ?: '-' }}</td>
   <td>{{ $status }}</td>
   <td>{{ $order->created_at?->format('d-m-Y H:i') ?? '-' }}</td>
  </tr>
  @endforeach
 </tbody>
</table>