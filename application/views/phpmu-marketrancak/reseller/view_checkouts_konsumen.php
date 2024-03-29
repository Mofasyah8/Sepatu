<?php 
$this->session->unset_userdata('sopir1');
$proses = '<i class="text-danger">Pending</i>'; 
$total = $this->db->query("SELECT sum((a.harga_jual*a.jumlah)-a.diskon) as total, sum(b.berat*a.jumlah) as total_berat FROM `rb_penjualan_temp` a JOIN rb_produk b ON a.id_produk=b.id_produk where a.session='".$this->session->idp."'")->row_array();
$kupon = $this->db->query("SELECT sum(b.nilai_kupon) as diskon FROM `rb_penjualan_temp` a JOIN rb_produk_kupon b ON a.id_kupon=b.id_kupon where a.session='".$this->session->idp."'")->row_array();
?>
<div class="ps-breadcrumb">
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="#">Home</a></li>
            <li><a href="<?php echo base_url()."produk/keranjang"; ?>">Keranjang <?php echo $this->session->sopir1; ?></a></li>
            <li><?php echo $title; ?></li>
        </ul>
    </div>
</div>
<div class="ps-section--shopping ps-shopping-cart">
    <div class="container">
        <div class="ps-section__content">
            <div class="table-responsive">
              <?php echo "<form action='".base_url()."produk/selesai_belanja' method='POST'>"; ?>
                <div class="row">
                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12 ">
                <?php 
                $kon = $this->db->query("SELECT * FROM rb_konsumen where id_konsumen='".$this->session->id_konsumen."'")->row_array();
                  echo "<div class='form-group row' style='margin-bottom:5px'>
                        <label class='col-sm-2 col-form-label' style='margin-bottom:1px; font-weight:bold'>Dikirim Kepada</label>
                        <div class='col-sm-10'>
                            $kon[nama_lengkap]
                        </div>
                        </div>

                        <div class='form-group row' style='margin-bottom:5px'>
                        <label class='col-sm-2 col-form-label' style='margin-bottom:1px; font-weight:bold'>No Hp/Telpon</label>
                        <div class='col-sm-10'>
                            ".substr($kon['no_hp'], 0, -2)."xx
                        </div>
                        </div>";

                            echo "<div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-2 col-form-label' style='margin-bottom:1px; font-weight:bold'>Alamat Kirim</label>
                            <div class='col-sm-10'>
                                <div class='form-row'>
                                    <div class='form-group col-md-4' style='margin-bottom:5px'>
                                    <select class='form-control form-mini' name='provinsi' id='list_provinsi' required>";
                                    echo "<option value=''>- Pilih Provinsi -</option>";
                                    $provinsi = $this->db->query("SELECT * FROM tb_ro_provinces ORDER BY province_name ASC");
                                    foreach ($provinsi->result_array() as $row) {
                                      if ($kon['provinsi_id']==$row['province_id']){
                                        echo "<option value='$row[province_id]' selected>$row[province_name]</option>";
                                      }else{
                                        echo "<option value='$row[province_id]'>$row[province_name]</option>";
                                      }
                                    }
                                    echo "</select>
                                    </div>
                                    <div class='form-group col-md-4' style='margin-bottom:5px'>
                                    <select class='form-control form-mini' name='kota' id='list_kotakab' required>";
                                    echo "<option value=''>- Pilih Kota / Kabupaten -</option>";
                                    $kota = $this->db->query("SELECT * FROM tb_ro_cities where province_id='$kon[provinsi_id]' ORDER BY city_name ASC");
                                    foreach ($kota->result_array() as $row) {
                                      if ($kon['kota_id']==$row['city_id']){
                                        echo "<option value='$row[city_id]' selected>$row[city_name]</option>";
                                      }else{
                                        echo "<option value='$row[city_id]'>$row[city_name]</option>";
                                      }
                                    }
                                    echo "</select>
                                    </div>
                                    <div class='form-group col-md-4' style='margin-bottom:5px'>
                                    <select class='form-control form-mini' name='kecamatan' id='list_kecamatan' required>";
                                    echo "<option value=''>- Pilih Kecamatan -</option>";
                                    $subdistrict = $this->db->query("SELECT * FROM tb_ro_subdistricts where city_id='$kon[kota_id]' ORDER BY subdistrict_name ASC");
                                    foreach ($subdistrict->result_array() as $row) {
                                      if ($kon['kecamatan_id']==$row['subdistrict_id']){
                                        echo "<option value='$row[subdistrict_id]' selected>$row[subdistrict_name]</option>";
                                      }else{
                                        echo "<option value='$row[subdistrict_id]'>$row[subdistrict_name]</option>";
                                      }
                                    }
                                    echo "</select>
                                    </div>
                                </div>
                                <input type='text' name='alamat' class='form-control form-mini' value='$kon[alamat_lengkap]' placeholder='Nama Jalan, No Rumah/Kantor anda..' autocomplete='off' required>
                            </div>
                            </div>

                            <div class='form-group row'>
                            <label class='col-sm-2 col-form-label' style='margin-bottom:1px; font-weight:bold; padding: 0px 0px 0px 15px;'>Kordinat Lokasi</b></label>
                              <div class='col-sm-10'>
                                <input type='text' class='form-control form-mini btn-geolocationx' value='$kon[kordinat_lokasi]' name='kordinat_lokasi' id='lokasi_pembeli' autocomplete='off' />
                                <label class='switch mr-1 mt-2'>
                                  <input type='checkbox' name='alamat_lainx' id='alamat_lain'> Cari Kordinat anda dari Peta <small style='color:red'>(Untuk Pengiriman Kurir Lokal)</small>
                                </label>
                              </div>
                            </div>
                            
                            <div class='show-map'>
                                <div id='mapid' class='shadow-sm'></div>
                            </div>
                            
                            <div style='padding:5px; font-size:16px; font-weight:bold; background:#f4f4f4; border-bottom:1px solid #ab0534; margin-bottom:10px;'>Data Produk</div>";
                          $no = 1;
                          foreach ($record as $row){
                          $re = $this->model_app->view_where('rb_reseller',array('id_reseller'=>$row['id_reseller']))->row_array();
                          $sub_total = (($row['harga_jual']-$row['diskon'])*$row['jumlah']);
                          $ex = explode(';', $row['gambar']);
                          if ($row['pre_order']!='' AND $row['pre_order']>0){
                            $pre_order = "<span class='badge badge-secondary'>Pre-Order $row[pre_order] Hari</span>";
                          }else{
                            $pre_order = "";
                          }
                          if (trim($ex[0])=='' OR !file_exists("asset/foto_produk/".$ex[0])){ $foto_produk = 'no-image.png'; }else{ if (!file_exists("asset/foto_produk/thumb_".$ex[0])){ $foto_produk = $ex[0]; }else{ $foto_produk = "thumb_".$ex[0]; }}
                          echo "<a style='font-size:17px; display:block; border-bottom:1px dotted' href='".base_url()."produk/detail/$row[produk_seo]'>$row[nama_produk]</a>
                                <div class='ps-product--cart'>
                                    <input type='hidden' name='id$no' value='$row[id_penjualan_detail]'> 
                                    <div class='ps-product__thumbnail'>
                                        <div style='height:60px; overflow:hidden'><a href='".base_url()."produk/detail/$row[produk_seo]'><img style='padding-right:10px' src='".base_url()."asset/foto_produk/$foto_produk' alt='$row[nama_produk]'></a></div>
                                    </div>
                                    
                                    <div class='ps-product__content' style='text-align:left; padding-left:0px'>
                                    <p style='margin-bottom:0'>$row[nama_reseller] $pre_order</p>
                                        <b>Qty.</b> $row[jumlah] x ".rupiah($row['harga_jual']-$row['diskon'])." = <b>".rupiah($sub_total)."</b><br>";
                                        $catatan = explode('||',$row['keterangan_order']);
                                        // $variasi = $this->db->query("SELECT * FROM rb_produk_variasi where id_produk='$row[id_produk]' ORDER BY id_variasi ASC");
                                        // if ($variasi->num_rows()>0){
                                        //     $noo = 1;
                                        //     $ex = explode(';',$catatan[1]);
                                        //     for ($ii=0; $ii < count($ex) ; $ii++) { 
                                        //         $exx = explode('|',$ex[$ii]);
                                        //         $variasi_terpilih[] = trim($exx[0]);
                                        //     }
                                        //     foreach ($variasi->result_array() as $va) {
                                        //         if ($noo%2 == 1){ $bg = '#e3e3e3'; }else{ $bg = '#f4f4f4'; }
                                        //         echo "<div style='background:$bg; padding:3px 10px; display:inline-block'><b>$va[nama]</b> : "; 
                                        //         $variasi = explode(";",$va['variasi']);
                                        //         for ($i=0; $i < count($variasi) ; $i++) { 
                                        //             $nama_variasi = "variasi".$noo.$i.$no;
                                        //             $_ck = (array_search($nama_variasi, $variasi_terpilih) === false)? '' : 'checked';
                                        //             if ($_ck=='checked'){
                                        //               echo "<span style='color:blue'>".$variasi[$i]."</span> &nbsp; ";
                                        //             }
                                        //         }
                                        //         echo "</div>";
                                        //         $noo++;
                                        //     }
                                        //     echo "<br>";
                                        // }
                                        if (trim($catatan[1])!=''){
                                          echo "<b>Variasi</b> : ".$catatan[1].'<br>';
                                        }
                                        if (trim($catatan[0])!=''){
                                          echo "<b>Catatan</b> : ".$catatan[0];
                                        }

                                    echo "</div>
                                </div><br>";
                            $no++;
                          }
                ?>
                </div>

                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 ">
                    <div class="ps-block--shopping-total">
                        <div class="ps-block__content">
                            <ul class="ps-block__product">
                                <?php
                                  if ($this->session->idp != ''){
                                    $noo = 1;
                                    $reseller_order = $this->db->query("SELECT a.*, e.nama_reseller, e.kordinat, e.alamat_lengkap, e.keterangan, e.kecamatan_id, e.kota_id, e.pilihan_kurir, b.id_reseller, c.nama_kota, d.nama_provinsi FROM `rb_penjualan_temp` a JOIN rb_produk b ON a.id_produk=b.id_produk 
                                    JOIN rb_reseller e ON b.id_reseller=e.id_reseller
                                    JOIN rb_kota c ON e.kota_id=c.kota_id 
                                    JOIN rb_provinsi d ON c.provinsi_id=d.provinsi_id where a.session='".$this->session->idp."' GROUP BY b.id_reseller"); 
                                    foreach ($reseller_order->result_array() as $res) {
                                      $ber = $this->db->query("SELECT sum(b.berat*a.jumlah) as total_berat FROM `rb_penjualan_temp` a JOIN rb_produk b ON a.id_produk=b.id_produk where a.session='".$this->session->idp."' AND b.id_reseller='$res[id_reseller]'")->row_array();
                                      $kota_asal[] = $res['kota_id'];
                                      $berat[] = $ber['total_berat'];
                          
                                      echo "<div class='form-group'>
                                            <label style='display:block'>
                                            <div class='pelapak'>
                                              <p style='margin-bottom:0px'>Toko : <b>$res[nama_reseller]</b></p>
                                              Alamat : ".kecamatan($res['kecamatan_id'],$res['kota_id'])."<br>
                                              <input type='hidden' name='toko$noo' value='$res[id_reseller]'>
                                              <input type='hidden' id='lokasi_penjual$noo' value='$res[kordinat]'>
                                            </div>
                                            </label>";

                                            $cekk = $this->db->query("SELECT jenis_produk FROM rb_penjualan_temp a JOIN rb_produk b ON a.id_produk=b.id_produk where a.session='".$this->session->id_konsumen."' GROUP BY b.jenis_produk")->row_array();
                                            if ($cekk['jenis_produk']=='Fisik'){
                                              // Ongkir dan Kurir jika produk fisik
                                                echo "<label style='display:block; cursor:pointer; margin-bottom:0rem'>
                                                  <input type='checkbox' name='kurir' class='kurir$noo' value='cod'/> <span style='display:inline-block'>Kirim via COD (Bayar Di tempat)</span>
                                                </label>
                                                <input type='hidden' id='list_kecamatan_dari$noo' value='$res[kecamatan_id]'>";

                                                echo "<select class='form-control form-mini text-success' name='kode_sopir$noo' id='list_sopir$noo' style='margin-bottom:2px; ".($res['kordinat']!=''?'':'color:red !important')."' ".($res['kordinat']!=''?'':'disabled').">
                                                  <option value='0'>".($res['kordinat']!=''?'- Kirim via Kurir Lokal -':'- Kurir Lokal Tidak tersedia -')."</option>";
                                                  if ($res['kordinat']!=''){
                                                    $kurir_sopir = $this->model_app->view_ordering('rb_jenis_kendaraan','id_jenis_kendaraan','ASC');
                                                    foreach ($kurir_sopir as $rowk) {
                                                      echo "<option value='$rowk[id_jenis_kendaraan]'>$rowk[jenis_kendaraan]</option>";
                                                    }
                                                  }
                                                echo "</select>";

                                                echo "<select class='form-control form-mini text-success' name='kode_kurir$noo' id='list_kurir$noo' style='margin-bottom: 2px'>
                                                    <option value='0'>- Kirim via Kurir Lainnya -</option>";
                                                    $kurir_data = $this->model_app->view_ordering('rb_kurir','id_kurir','ASC');
                                                    if ($res['pilihan_kurir']==''){
                                                      foreach ($kurir_data as $rowk) {
                                                        echo "<option value='$rowk[kode_kurir]'>$rowk[nama_kurir]</option>";
                                                      }
                                                    }else{
                                                      $kurir_terpilih = explode(',',$res['pilihan_kurir']);
                                                      foreach ($kurir_data as $rowk) {
                                                        foreach ($kurir_terpilih as $select_option){
                                                          if($rowk['id_kurir'] == $select_option) {
                                                            echo "<option value='$rowk[kode_kurir]'>$rowk[nama_kurir]</option>";
                                                            break;
                                                          }
                                                        }
                                                      }
                                                    }

                                                echo "</select>
                                                <ul class='list-group list-group-flush'>
                                                  <div id='list_sopir_div$noo'></div>
                                                </ul>

                                                <ul class='list-group list-group-flush'>
                                                    <div id='list_kurir_div$noo'></div>
                                                </ul>

                                                <ul class='list-group list-group-flush' id='kurir-list$noo'>";
                                                    if ($this->session->id_konsumen==''){
                                                      $cod = $this->db->query("SELECT * FROM rb_reseller_cod where id_reseller='$res[id_reseller]'");
                                                    }else{
                                                      $ress = $this->model_reseller->penjualan_konsumen_detail($this->session->idp)->row_array();
                                                      $cod = $this->db->query("SELECT * FROM rb_reseller_cod where id_reseller='$res[id_reseller]'");
                                                    }
                                                    $service = 1;
                                                    foreach ($cod->result_array() as $ros) {
                                                      echo '<li id="'.$noo.$idn.'serv-'.$service.'" class="list-group-item clearall-kurir" style="cursor:pointer; margin:1px; padding-bottom: 5px; padding:5px 1.25rem; line-height: 1;" onclick="klikongkir'.$noo.'(\'COD (Cash on delivery)\',\''.$ros['nama_alamat'].'\',\''.$ros['biaya_cod'].'\',\''.number_format($ros['biaya_cod'],0).'\',this.id)">
                                                                <span style="color:black;">COD - '.$ros['nama_alamat'].'</span><small><b>Tarif.</b> <b style="color:red">Rp '.number_format($ros['biaya_cod'],0).'</b> - Bayar Di tempat</small>
                                                          </li>';
                                                      $service++;
                                                    }
                            
                                                    if ($cod->num_rows()<=0){
                                                      echo "<center style='color:red'>COD Tidak Tersedia!</center>";
                                                    }
                            
                                                echo "</ul>";
                                            }else{
                                              
                                            }


                                              
                                          echo "</div>";
                                      $noo++;
                                    }
                                  }
                                ?>
                            </ul>
                            <hr>
                            <div class="ps-block__header">

                                <?php if ($cekk['jenis_produk']=='Fisik'){ ?>
                                <p style='margin-bottom:0px'>Berat<span> <?php echo "$total[total_berat] Gram"; ?></span></p>
                                <p style='margin-bottom:0px'>Ongkos Kirim <span> <input type='text' id='ongkir_view' style='background:none; text-align:right; width:110px' value='0' disabled/></span></p>
                                <?php } ?>

                                <?php 
                                  $ref = $this->model_app->view_where('rb_setting',array('id_setting'=>'1'))->row_array();
                                  $fv = explode('|',$ref['keterangan']);
                                  if ($fv[0]>'0'){
                                    $fee_admin = $fv[0];
                                    echo "<p style='margin-bottom:0px'>Fee Admin <span>Rp ".rupiah($fv[0])."</span></p>";
                                  }else{
                                    $fee_admin = 0;
                                  }
                                ?>
                                <p style='margin-bottom:0px'>Subtotal <span> <?php echo "Rp ".rupiah($total['total']-$total['diskon_total']); ?></span></p>

                                <div class="form-group--nest" style='margin-top:10px'>
                                    <input class="form-control" name='kode_kupon' id='kode_kupon' type="text" placeholder="Kode Kupon / Voucher">
                                    <button type='button' id='submit_kupon' class="ps-btn"><span class='fa fa-check'></span></button>
                                </div>
                                <div class='kupon_list'></div>
                            </div>
                            <span class='reff'><h3>Total <span id='totalbayar'></span></h3></span>
                            
                            <hr><p style='font-size:1.6rem; font-weight:600; margin-bottom:0px'>Pilih Metode Pembayaran :</p>
                            <input type='radio' name='metode' value='transfer' checked> Transfer Bank<br>
                            <input type='radio' name='metode' id='saldo' value='saldo'>  Saldo Akun (<?php echo "Rp ".rupiah(saldo(reseller($this->session->id_konsumen),$this->session->id_konsumen)); ?>)
                            
                            <?php if ($cekk['jenis_produk']=='Fisik'){ ?>
                              <hr><p style='font-size:1.6rem; font-weight:600; margin-bottom:15px'>Kirim Sebagai Dropshipper</p>
                              <?php 
                                $dropshipp = array('Tidak','Ya');
                                echo "<select class='form-control' name='dropshipp' id='dropshipp'>";
                                for ($i=0; $i < count($dropshipp); $i++) { 
                                  echo "<option value='".$dropshipp[$i]."'>".$dropshipp[$i]."</option>";
                                }
                                echo "</select>
                                
                                <input type='text' class='form-control dropshipp' name='nama_dropshipp' placeholder='Nama Pengirim....'>
                                <input type='text' class='form-control dropshipp' name='telp_dropshipp' placeholder='No. Telepon Pengirim....'>";
                              }
                            ?>
                        </div>
                    </div>
                    <?php if ($cekk['jenis_produk']=='Fisik'){ ?>
                      <button type='submit' name='submit' id='oksimpan' style='display: none' class="ps-btn ps-btn--fullwidth">Proses Pembayaran</a>
                      <button type='button' id='oksimpanx' style='background:#e3e3e3; color:#000 !important; border:1px solid #000' class="ps-btn ps-btn--fullwidth oksimpanx">Proses Pembayaran</a>
                    <?php }else{ ?>
                      <button type='submit' name='submit' id='oksimpan_digital' style='display: block' class="ps-btn ps-btn--fullwidth">Proses Pembayaran</a>
                    <?php } ?>
                </div>
                </div>
                <?php 
                  echo "<input type='hidden' id='fee_admin' value='$fee_admin'/>
                        <input type='hidden' id='kupon' value='0'/>
                        <input type='hidden' name='totalx' id='totalx' value='".($total['total']+$fee_admin)."'/>
                        <input type='hidden' name='total' id='total' value='".(($total['total']+$fee_admin)-$kupon['diskon'])."'/>
                        <input type='hidden' name='ongkir' id='ongkir' style='color:red' value=''/>
                        <input type='hidden' name='berat' value='$total[total_berat]'/>
                        <input type='hidden' name='diskonnilai' id='diskonnilai' value='$diskon_total'/>
                        <input type='hidden' name='ongkir1' id='ongkir1' value='0'/>
                        <input type='hidden' name='service1' id='service1'/>
                        <input type='hidden' name='kurir1' id='kurir1'/>
                        <input type='hidden' name='ongkir2' id='ongkir2' value='0'/>
                        <input type='hidden' name='service2' id='service2'/>
                        <input type='hidden' name='kurir2' id='kurir2'/>
                        <input type='hidden' name='ongkir3' id='ongkir3' value='0'/>
                        <input type='hidden' name='service3' id='service3'/>
                        <input type='hidden' name='kurir3' id='kurir3'/>
                        <div class='kota'></div>";
                ?>
                </form>
            </div>
        </div>
        <hr>
    </div>
</div>


<script>
$(document).ready(function(){
  $(".dropshipp").attr("style", "display:none");
  var dropshipp = jQuery('#dropshipp');
  dropshipp.change(function () {
      if ($(this).val() == 'Ya') {
        $(".dropshipp").attr("style", "display:block; margin:3px 0px; border:1px solid #00b500;");
        $(".dropshipp").prop('required',true);
      }else{
        $(".dropshipp").attr("style", "display:none");
        $(".dropshipp").removeAttr('required');
      }
  });

  show_kupon_list();
  function show_kupon_list(){
      $.ajax({
          url   : '<?php echo site_url("members/kupon_list"); ?>',
          type  : 'GET',
          async : true,
          dataType : 'json',
          success : function(data){
              var html = '';
              var i;
              for(i=0; i<data.length; i++){
                  html += '<p style="margin-bottom:0px">'+
                          '<a href="javascript:void(0);" class="ps-product__remove kupon_delete" style="cursor:pointer" data-id_penjualan_detail="'+data[i].id_penjualan_detail+'"><i style="color:red" class="fa fa-remove"></i></a> '+
                          '<b style="color:green">'+data[i].kode_kupon+'</b>'+
                          '<span>Rp -'+toRupiah(data[i].nilai_kupon)+'</span></p>';
              }
              $('.kupon_list').html(html);
          }
      });
  } 

  function sum_kupon_list(){
      $.ajax({
          url   : '<?php echo site_url("members/kupon_list_sum"); ?>',
          type  : 'GET',
          async : true,
          dataType : 'json',
          success : function(data){
              var i;
              for(i=0; i<data.length; i++){
                tot = $('#totalx').val();
                hasil = tot-data[i].total_nilai_kupon;
                $('#total').val(hasil);
              }
              hitung();
          }
      });
  } 

  $('#submit_kupon').on('click',function(){
    var kode_kupon = $('#kode_kupon').val();
    $.ajax({
        type : "POST",
        url  : "<?php echo site_url('members/kupon_used')?>",
        dataType : "JSON",
        data : {kode_kupon:kode_kupon},
        success: function(data){
          if(data==true){
            $('[name="kode_kupon"]').val("");
            show_kupon_list();
            sum_kupon_list();
            hitung();
          }else{
            $('#Modal_Notif').modal('show');
            $('#error_notif').html(data.pesan);
            // alert(data.pesan);
          }
        }
    });
    return false;
  });

  $('.kupon_list').on('click','.kupon_delete',function(){
    var id = $(this).data('id_penjualan_detail');
      $.ajax({
          type : "POST",
          url  : "<?php echo site_url('members/kupon_cart_delete')?>",
          dataType : "JSON",
          data : {id:id},
          success: function(data){
            show_kupon_list();  
            sum_kupon_list();
            hitung();
          }
      });
      return false;
  });

});



/*$(document).ready(function(){
    $("#submit").on("click", function(){
    var a = parseInt($('#a').val());
    var b = parseInt($('#b').val());
        var sum = a + b;
        $("#ongkir").val(sum);
    })
})*/
$("#form_alamat").hide();

$("#kurir-list1").hide();
$(".kurir1").change(function(){
    $("#kurir-list1").toggle();
});


$("#kurir-list2").hide();
$(".kurir2").change(function(){
    $("#kurir-list2").toggle();
});

$("#kurir-list3").hide();
$(".kurir3").change(function(){
    $("#kurir-list3").toggle();
});

function klikongkir1(data,detail,harga,harga_rp,id){
  $("#ongkir1").val(harga);
  $(".clearall-kurir").removeClass("selected-ongkir1");
  $(".clearall-kurir").removeClass("selected-ongkir10");
  $('#'+id).addClass("selected-ongkir1");
  $('#service1').val(detail);
  $('#kurir1').val(data);
  var val1 = +$("#ongkir1").val();
  var val2 = +$("#ongkir2").val();
  var val3 = +$("#ongkir3").val();
  $("#ongkir").val(val1+val2+val3);
  $("#ongkir_view").val(toDuit(val1+val2+val3));
  $("#oksimpan").show();
  $("#oksimpanx").hide();
  hitung();
}

function klikongkir2(data,detail,harga,harga_rp,id){
  $("#ongkir2").val(harga);
  $(".clearall-kurir").removeClass("selected-ongkir2");
  $(".clearall-kurir").removeClass("selected-ongkir11");
  $('#'+id).addClass("selected-ongkir2");
  $('#service2').val(detail);
  $('#kurir2').val(data);
  var val1 = +$("#ongkir1").val();
  var val2 = +$("#ongkir2").val();
  var val3 = +$("#ongkir3").val();
  $("#ongkir").val(val1+val2+val3);
  $("#ongkir_view").val(toDuit(val1+val2+val3));
  hitung();
}

function klikongkir3(data,detail,harga,harga_rp,id){
  $("#ongkir3").val(harga);
  $(".clearall-kurir").removeClass("selected-ongkir3");
  $(".clearall-kurir").removeClass("selected-ongkir12");
  $('#'+id).addClass("selected-ongkir3");
  $('#service3').val(detail);
  $('#kurir3').val(data);
  var val1 = +$("#ongkir1").val();
  var val2 = +$("#ongkir2").val();
  var val3 = +$("#ongkir3").val();
  $("#ongkir").val(val1+val2+val3);
  $("#ongkir_view").val(toDuit(val1+val2+val3));
  hitung();
}


function klikongkir10(data,detail,harga,harga_rp,id){
  $("#ongkir1").val(harga);
  $(".clearall-kurir").removeClass("selected-ongkir10");
  $(".clearall-kurir").removeClass("selected-ongkir1");
  $('#'+id).addClass("selected-ongkir10");
  $('#service1').val(detail);
  $('#kurir1').val(data);
  var val1 = +$("#ongkir1").val();
  var val2 = +$("#ongkir2").val();
  var val3 = +$("#ongkir3").val();
  $("#ongkir").val(val1+val2+val3);
  $("#ongkir_view").val(toDuit(val1+val2+val3));
  $("#oksimpan").show();
  $("#oksimpanx").hide();
  hitung();
}

function klikongkir11(data,detail,harga,harga_rp,id){
  $("#ongkir2").val(harga);
  $(".clearall-kurir").removeClass("selected-ongkir11");
  $(".clearall-kurir").removeClass("selected-ongkir2");
  $('#'+id).addClass("selected-ongkir11");
  $('#service2').val(detail);
  $('#kurir2').val(data);
  var val1 = +$("#ongkir1").val();
  var val2 = +$("#ongkir2").val();
  var val3 = +$("#ongkir3").val();
  $("#ongkir").val(val1+val2+val3);
  $("#ongkir_view").val(toDuit(val1+val2+val3));
  $("#oksimpan").show();
  $("#oksimpanx").hide();
  hitung();
}

function klikongkir12(data,detail,harga,harga_rp,id){
  $("#ongkir3").val(harga);
  $(".clearall-kurir").removeClass("selected-ongkir12");
  $(".clearall-kurir").removeClass("selected-ongkir3");
  $('#'+id).addClass("selected-ongkir12");
  $('#service3').val(detail);
  $('#kurir3').val(data);
  var val1 = +$("#ongkir1").val();
  var val2 = +$("#ongkir2").val();
  var val3 = +$("#ongkir3").val();
  $("#ongkir").val(val1+val2+val3);
  $("#ongkir_view").val(toDuit(val1+val2+val3));
  $("#oksimpan").show();
  $("#oksimpanx").hide();
  hitung();
}

$(document).ready(function(){
//* select Provinsi */
var base_url    = "<?php echo base_url();?>";
$("#list_provinsi").change(function(){
    var id_province = this.value;
    kota(id_province);
    $("#div_kota").show();
});

/* select Kota */
kota = function(id_province){
    $.ajax({
    type: 'post',
    url: base_url + 'produk/rajaongkir_get_kota',
    data: {id_province:id_province},
    dataType  : 'html',
    success: function (data) {
        $("#list_kotakab").html(data);
    },
    beforeSend: function () {
        
    },
    complete: function () {
      
    }
});
}

$("#list_kotakab").change(function(){
    var id_kota = this.value;
    kecamatan(id_kota);
    $("#div_kecamatan").show();
});

$("#list_kecamatan").change(function(){
    $(".clearall-kurir").removeClass("selected-ongkir1");
    $(".clearall-kurir").removeClass("selected-ongkir2");
    $(".clearall-kurir").removeClass("selected-ongkir3");
});

kecamatan = function(id_kota){
    $.ajax({
    type: 'post',
    url: base_url + 'produk/rajaongkir_get_kecamatan',
    data: {id_kota:id_kota},
    dataType  : 'html',
    success: function (data) {
        $("#list_kecamatan").html(data);
    }
});
}


$("#list_sopir1").change(function(){
    var id_kurir     = this.value;
    var id_kecamatan      = $("#list_kecamatan").val();
    var id_kecamatan_dari      = $("#list_kecamatan_dari1").val();
    var lokasi = $("#lokasi_pembeli").val();
    var lokasi_penjual = $("#lokasi_penjual1").val();
    sopircost1(id_kurir,id_kecamatan,id_kecamatan_dari,lokasi,lokasi_penjual);
    $("#div_sopir1").show();
});

sopircost1 = function(id_kurir,id_kecamatan,id_kecamatan_dari,lokasi,lokasi_penjual){
    $.ajax({
    type: 'post',
    url: base_url + 'produk/sopir_get_cost/10/<?php echo $kota_asal[0]; ?>/<?php echo $berat[0]; ?>',
    data: {kurir_pengiriman:id_kurir,kecamatan_dari:id_kecamatan_dari,kecamatan_tujuan:id_kecamatan,lokasi: lokasi, lokasi_penjual: lokasi_penjual},
    dataType  : 'html',
    success: function (data) {
      $("#list_sopir_div1").html(data);
    }
});
}

$("#list_sopir2").change(function(){
    var id_kurir     = this.value;
    var id_kecamatan      = $("#list_kecamatan").val();
    var id_kecamatan_dari      = $("#list_kecamatan_dari2").val();
    var lokasi = $("#lokasi_pembeli").val();
    var lokasi_penjual = $("#lokasi_penjual2").val();
    sopircost2(id_kurir,id_kecamatan,id_kecamatan_dari,lokasi,lokasi_penjual);
    $("#div_sopir2").show();
});

sopircost2 = function(id_kurir,id_kecamatan,id_kecamatan_dari,lokasi,lokasi_penjual){
    $.ajax({
    type: 'post',
    url: base_url + 'produk/sopir_get_cost/11/<?php echo $kota_asal[0]; ?>/<?php echo $berat[0]; ?>',
    data: {kurir_pengiriman:id_kurir,kecamatan_dari:id_kecamatan_dari,kecamatan_tujuan:id_kecamatan,lokasi: lokasi, lokasi_penjual: lokasi_penjual},
    dataType  : 'html',
    success: function (data) {
        $("#list_sopir_div2").html(data);
    }
});
}

$("#list_sopir3").change(function(){
    var id_kurir     = this.value;
    var id_kecamatan      = $("#list_kecamatan").val();
    var id_kecamatan_dari      = $("#list_kecamatan_dari3").val();
    var lokasi = $("#lokasi_pembeli").val();
    var lokasi_penjual = $("#lokasi_penjual3").val();
    sopircost3(id_kurir,id_kecamatan,id_kecamatan_dari,lokasi,lokasi_penjual);
    $("#div_sopir3").show();
});

sopircost3 = function(id_kurir,id_kecamatan,id_kecamatan_dari,lokasi,lokasi_penjual){
    $.ajax({
    type: 'post',
    url: base_url + 'produk/sopir_get_cost/12/<?php echo $kota_asal[0]; ?>/<?php echo $berat[0]; ?>',
    data: {kurir_pengiriman:id_kurir,kecamatan_dari:id_kecamatan_dari,kecamatan_tujuan:id_kecamatan,lokasi: lokasi, lokasi_penjual: lokasi_penjual},
    dataType  : 'html',
    success: function (data) {
        $("#list_sopir_div3").html(data);
    }
});
}

$("#list_kurir1").change(function(){
    var id_kurir     = this.value;
    var id_kecamatan      = $("#list_kecamatan").val();
    cost1(id_kurir,id_kecamatan);
    $("#div_kurir1").show();
});

cost1 = function(id_kurir,id_kecamatan){
    $.ajax({
    type: 'post',
    url: base_url + 'produk/rajaongkir_get_cost/1/<?php echo $kota_asal[0]; ?>/<?php echo $berat[0]; ?>',
    data: {kurir_pengiriman:id_kurir,kecamatan_tujuan:id_kecamatan},
    dataType  : 'html',
    success: function (data) {
        $("#list_kurir_div1").html(data);
    }
});
}

$("#list_kurir2").change(function(){
    var id_kurir     = this.value;
    var id_kecamatan      = $("#list_kecamatan").val();
    cost2(id_kurir,id_kecamatan);
    $("#div_kurir2").show();
});

cost2 = function(id_kurir,id_kecamatan){
    $.ajax({
    type: 'post',
    url: base_url + 'produk/rajaongkir_get_cost/2/<?php echo $kota_asal[1]; ?>/<?php echo $berat[1]; ?>',
    data: {kurir_pengiriman:id_kurir,kecamatan_tujuan:id_kecamatan},
    dataType  : 'html',
    success: function (data) {
        $("#list_kurir_div2").html(data);
    }
});
}

$("#list_kurir3").change(function(){
    var id_kurir     = this.value;
    var id_kecamatan      = $("#list_kecamatan").val();
    cost3(id_kurir,id_kecamatan);
    $("#div_kurir3").show();
});

cost3 = function(id_kurir,id_kecamatan){
    $.ajax({
    type: 'post',
    url: base_url + 'produk/rajaongkir_get_cost/3/<?php echo $kota_asal[2]; ?>/<?php echo $berat[2]; ?>',
    data: {kurir_pengiriman:id_kurir,kecamatan_tujuan:id_kecamatan},
    dataType  : 'html',
    success: function (data) {
        $("#list_kurir_div3").html(data);
    }
});
}

$(".alamat").click(function(){
    $("#form_alamat").toggle();
});

$("#diskon").html(toDuit(0));
hitung();
});

</script>

<script>
$('document').ready(function(){
    $('#assign').click(function(){
    var ag = $('#multiple_select').val();
        $('[name="pilihan_kurir"]').val(ag);
    });

    $("body").on("click", "input[name='alamat_lainx']", function () {
      if ($('#alamat_lain').is(':checked')) {
        $(".show-map").show();
        showMapsx();
      }else{
        $(".btn-geolocationx").val('');
        $(".show-map").hide();
      }
    });
});

function showMapsx() {
  // MAPS
  var mymap = L.map("mapid").setView(
    [<?php echo ($kon['kordinat_lokasi']==''?config('kordinat'):$kon['kordinat_lokasi']); ?>],
    15
  );
  L.tileLayer(
    "https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw", {
      maxZoom: 18,
      attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, ' +
        'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
      id: "mapbox/streets-v11",
      tileSize: 512,
      zoomOffset: -1,
    }
  ).addTo(mymap);

  L.marker([<?php echo ($kon['kordinat_lokasi']==''?config('kordinat'):$kon['kordinat_lokasi']); ?>])
    .addTo(mymap)
    .bindPopup("Silahkan klik map untuk mendapatkan koordinat.")
    .openPopup();

  var popup = L.popup();

  function onMapClick(e) {
    popup
      .setLatLng(e.latlng)
      .setContent(
        "Map yang anda klik berada di " + e.latlng.lat + ", " + e.latlng.lng
      )
      .openOn(mymap);
    document.getElementById("lokasi_pembeli").value =
      e.latlng.lat + ", " + e.latlng.lng;
  }

  // function onMapClick(e) {
  //   popup
  //     .setLatLng(e.latlng)
  //     .setContent(
  //       "Map yang anda klik berada di " + e.latlng.lat + ", " + e.latlng.lng
  //     )
  //     .openOn(mymap);
  //   document.getElementById("lokasi_pembeli").value =
  //     e.latlng.lat + ", " + e.latlng.lng;
  //   //
  //   setTimeout(() => {
  //     var lokasi = $("#lokasi_pembeli").val();
  //     var lokasi_penjual = $("#lokasi_penjual").val();
  //     $.ajax({
  //       url: "members/location",
  //       method: "POST",
  //       data: {
  //         lokasi: lokasi, lokasi_penjual: lokasi_penjual,
  //       },
  //       success: function (data) {
  //         jarak = data.split("||");
  //         var jarak_rp = jarak[0].toLocaleString();
  //         if (jarak[1] > 100) {
  //           alert("mohon maaf jarak anda lebih dari 100km");
  //         }else{
  //           $("#ring-ongkir").text("Rp. " + jarak_rp);
  //           $("#jarak").val(jarak[0]);
  //           var sumTotal = $(".sub-total").attr("id");
  //           var sumAll = +sumTotal + +jarak[0];
  //           $("#total-bayar").text("Rp. " + sumAll.toLocaleString());
  //         }
  //       },
  //     });
  //   }, 500);
  // }

  mymap.on("click", onMapClick);
}

$(window).ready(function () {
  $(".btn-geolocationx").click(findLocationx);
});

function findLocationx() {
  navigator.geolocation.getCurrentPosition(getCoordsx, handleErrorsx);
}

function getCoordsx(position) {
  $(".btn-geolocationx").val(
    position.coords.latitude + "," + position.coords.longitude
  );
}

function handleErrorsx(error) {
  switch (error.code) {
    case error.PERMISSION_DENIED:
      alert("You need to share your geolocation data.");
      break;

    case error.POSITION_UNAVAILABLE:
      alert("Current position not available.");
      break;

    case error.TIMEOUT:
      alert("Retrieving position timed out.");
      break;

    default:
      alert("Error");
      break;
  }
}

function hitung(){
    var diskon=$('#diskonnilai').val();
    var total=$('#total').val();
    var ongkir=$("#ongkir").val();
    var fee_admin=$("#fee_admin").val();
    if(parseFloat(ongkir) >= 0){
        $("#oksimpan").show();
        $("#oksimpanx").hide();
    }else{
        $("#oksimpan").hide();
        $("#oksimpanx").show();
    }
    
    ongkir = ongkir || 0;
    var bayar=(parseFloat(total)+parseFloat(ongkir));
    if (<?php echo saldo(reseller($this->session->id_konsumen),$this->session->id_konsumen); ?>>=bayar){
      $("#saldo").prop("disabled", false);
    }else{
      $("#saldo").prop("disabled", true); // disable
    }
    $("#totalbayar").html(toDuit(bayar));
}
</script>
