@props(['amount' => 0])
<span {{ $attributes }}>Rp{{ number_format((float) $amount, 0, ',', '.') }}</span>
