<div class="ps-page--single">
    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>">Home</a></li>
                <li><a href="#">Members</a></li>
                <li>Profile</li>
            </ul>
        </div>
    </div>
</div>
<div class="ps-vendor-dashboard pro" style='margin-top:10px'>
    <div class="container">
        <div class="ps-section__content">
            <?php include "menu-members.php"; ?>
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 ">
                    <?php
                      include "sidebar-members.php";
                      echo "<a href='".base_url()."members/edit_profile' class='ps-btn btn-block'><i class='icon-pen'></i> Edit Biodata Diri</a>";
                    ?><div style='clear:both'><br></div>
                </div>

                <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12 ">
                    <figure class="ps-block--vendor-status biodata">
                        <?php 
                            echo $this->session->flashdata('message'); 
                                 $this->session->unset_userdata('message');
                            $attributes = array('id' => 'formku');
                            
                            echo form_open_multipart('members/otp',$attributes); 
                            echo "<p style='font-size:17px'>Hai <b>$row[nama_lengkap] $otp</b>, Anda telah menerima pesan yang berisi kode verfikasi.<br>
                            Kode telah dikirim ke WA : <b>+".format_telpon($row['no_hp'])."</b> dan Email <b>$row[email]</b>.<br>
                            Jika Anda belum menerimanya, silahkan klik <a href='".base_url()."members/otp?resend'><u>disini</u></a></p><br><br>

                            <div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-3 col-form-label' style='margin-bottom:1px'>Two Factor Code</b></label>
                              <div class='col-sm-9'>
                              <input type='text' name='kode' class='form-control form-mini' autocomplete='off' placeholder='********' onkeyup=\"nospaces(this)\">
                              <input type='checkbox' name='remember' checked> Don't ask again for 30 days
                              </div>
                            </div>

                            <div class='form-group row' style='margin-bottom:5px'>
                            <label class='col-sm-3 col-form-label' style='margin-bottom:1px; background: #fff'></b></label>
                              <div class='col-sm-9'><br>
                                <input class='btn btn-sm btn-primary spinnerButton' type='submit' name='submit' value='Verify'>
                                <a class='btn btn-sm btn-danger' href='".base_url()."auth/logout'>Logout</a>
                              </div>
                            </div>

                            </form>";
                            
                        ?>
                    </figure>
                </div>
              
            </div>
        </div>
    </div>
</div>












<?php 
/*
echo "<table id='example11' class='table table-hover table-condensed'>
  <thead>
    <tr>
      <th width='20px'>No</th>
      <th>Nama Penjual</th>
      <th>Belanja & Ongkir</th>
      <th>Status</th>
      <th>Total + Ongkir</th>
      <th></th>
    </tr>
  </thead>
  <tbody>";

      $no = 1;
      $record = $this->model_reseller->orders_report($this->session->id_konsumen,'reseller');
      foreach ($record->result_array() as $row){
      if ($row['proses']=='0'){ $proses = '<i class="text-danger">Pending</i>'; }elseif($row['proses']=='1'){ $proses = '<i class="text-success">Proses</i>'; }else{ $proses = '<i class="text-info">Konfirmasi</i>'; }
      $total = $this->db->query("SELECT sum((a.harga_jual*a.jumlah)-a.diskon) as total FROM `rb_penjualan_detail` a where a.id_penjualan='$row[id_penjualan]'")->row_array();
      echo "<tr><td>$no</td>
                <td><a href='".base_url()."members/detail_reseller/$row[id_reseller]'><small><b>$row[nama_reseller]</b></small><br><small class='text-success'>$row[kode_transaksi]</small></a></td>
                <td><span style='color:blue;'>Rp ".rupiah($total['total'])."</span> <br> <small><i style='color:green;'><b style='text-transform:uppercase'>$row[kurir]</b> - Rp ".rupiah($row['ongkir'])."</i></small></td>
                <td>$proses <br><small>$row[nama_reseller]</small></td>
                <td style='color:red;'>Rp ".rupiah($total['total']+$row['ongkir'])."</td>
                <td width='130px'>";
                if ($row['proses']=='0'){
                  echo "<a style='margin-right:3px' class='btn btn-success btn-sm' title='Konfirmasi Pembayaran' href='".base_url()."konfirmasi?kode=$row[kode_transaksi]'>Konfirmasi</a>";
                }else{
                  echo "<a style='margin-right:3px' class='btn btn-default btn-sm' href='#'  onclick=\"return confirm('Maaf, Pembayaran ini sudah di konfirmasi!')\">Konfirmasi</a>";
                }
              
              echo "<a class='btn btn-info btn-sm' title='Detail data pesanan' href='".base_url()."members/keranjang_detail/$row[id_penjualan]'><span class='glyphicon glyphicon-search'></span></a></td>
            </tr>";
        $no++;
      }

  echo "</tbody>
</table>"; */
?>