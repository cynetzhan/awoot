<?php
function tanggal($dt,$with_timestamp=false,$shorten=false){
 //format harus yyyy-mm-dd
 $bulan=array(
  "00" => "N/A",
  "01" => "Januari",
  "02" => "Februari",
  "03" => "Maret",
  "04" => "April",
  "05" => "Mei",
  "06" => "Juni",
  "07" => "Juli",
  "08" => "Agustus",
  "09" => "September",
  "10" => "Oktober",
  "11" => "November",
  "12" => "Desember"
 );
 $date=explode("-",$dt);
 $tahun=substr($date[2],0,2); //fix date with timestamp format
 if($shorten){
  $bulan[$date[1]] = substr($bulan[$date[1]], 0, 3);
 }
 $tanggal=$tahun." ".$bulan[$date[1]]." ".$date[0];
 if($with_timestamp){
  $tanggal .= " ".substr($date[2],3);
 }
 return $tanggal;
}

function umur($tgl1){
    $pecah1 = explode("-", $tgl1);
    $date1 = $pecah1[2];
    $month1 = $pecah1[1];
    $year1 = $pecah1[0];
    $tgl2=date("Y-m-d");
    // memecah tanggal untuk mendapatkan bagian tanggal, bulan dan tahun
    // dari tanggal kedua

    $pecah2 = explode("-", $tgl2);
    $date2 = $pecah2[2];
    $month2 = $pecah2[1];
    $year2 =  $pecah2[0];

    // menghitung JDN dari masing-masing tanggal

    $jd1 = GregorianToJD($month1, $date1, $year1);
    $jd2 = GregorianToJD($month2, $date2, $year2);

    // hitung selisih hari kedua tanggal

    $selisih = $jd2 - $jd1;
    $umur=$selisih/365;
    return floor($umur);
}

function umurDetail($ex){
    $pecah=explode(" ",$ex);
    $tgl=$pecah[0];
    $tgl_lahir=substr($tgl,8,2);
    $bln_lahir=substr($tgl,5,2);
    $thn_lahir=substr($tgl,0,4);
    $tanggal_today = date('d');
    $bulan_today=date('m');
    $tahun_today = date('Y');
    $harilahir=gregoriantojd($bln_lahir,$tgl_lahir,$thn_lahir);
    //menghitung jumlah hari sejak tahun 0 masehi
    $hariini=gregoriantojd($bulan_today,$tanggal_today,$tahun_today);
    //menghitung jumlah hari sejak tahun 0 masehi
    $umur=$hariini-$harilahir;
    //menghitung selisih hari antara tanggal sekarang dengan tanggal lahir
    $tahun=$umur/365;//menghitung usia tahun
    $sisa=$umur%365;//sisa pembagian dari tahun untuk menghitung bulan
    $bulan=$sisa/30;//menghitung usia bulan
    $hari=$sisa%30;//menghitung sisa hari
    $lahir= "$tgl_lahir-$bln_lahir-$thn_lahir";
    $today= "$tanggal_today-$bulan_today-$tahun_today";
    if(floor($tahun)>0 AND floor($bulan)>0 AND $hari>0){
        return floor($tahun)." Thn ".floor($bulan)." Bln $hari Hr";
    }elseif(floor($tahun)>0 AND floor($bulan)==0 AND $hari>0){
        return floor($tahun)." Thn $hari Hr";
    }elseif(floor($tahun)>0 AND floor($bulan)>0 AND $hari==0){
        return floor($tahun)." Thn ".floor($bulan)." Bln";
    }elseif(floor($tahun)==0 AND floor($bulan)>0 AND $hari>0){
        return floor($bulan)." Bln $hari Hr";
    }elseif(floor($tahun)==0 AND floor($bulan)==0 AND $hari>0){
        return "$hari Hr";
    }elseif(floor($tahun)>0 AND floor($bulan)==0 AND $hari==0){
        return floor($tahun)." Thn";
    }
    
}