<?php

namespace App\Traits;

trait MoneyFormat
{
  public function formatRupiahSingkat($angka)
  {
    if ($angka >= 1000000) {

      $hasil = $angka / 1000000;

      // potong sampai 3 desimal (tanpa pembulatan)
      $hasil = floor($hasil * 100) / 100;

      $format = number_format($hasil, 3, ',', '.');

      // hapus nol & koma di belakang kalau tidak perlu
      $format = rtrim(rtrim($format, '0'), ',');

      return 'Rp ' . $format . ' jt';
    } elseif ($angka >= 100000) {

      $hasil = $angka / 1000;
      $hasil = floor($hasil * 100) / 100;

      $format = number_format($hasil, 3, ',', '.');
      $format = rtrim(rtrim($format, '0'), ',');

      return 'Rp ' . $format . ' k';
    } else {
      return 'Rp ' . number_format($angka, 0, ',', '.');
    }
  }

  public function formatRupiahFull($angka)
  {
    return 'Rp ' . number_format($angka, 0, ',', '.');
  }
}
