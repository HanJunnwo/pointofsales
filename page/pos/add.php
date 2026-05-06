<?php
include "./function/connection.php";

// Ambil semua data barang dari DB
$queryBarang = mysqli_query($connection, "SELECT kode_barang, nama_barang, harga_jual FROM tbl_master_barang ORDER BY nama_barang ASC");
$listBarang  = [];
while ($b = mysqli_fetch_assoc($queryBarang)) {
    $listBarang[] = $b;
}
$listBarangJson = json_encode($listBarang);

// Handle submit
if (isset($_POST['submit'])) {
    $kode_transaksi = "TRX" . time();
    $tanggal        = date('Y-m-d');
    $user           = $_SESSION['nama'] ?? 'admin';

    $kode_barang = $_POST['kode_barang'];
    $nama_barang = $_POST['nama_barang'];
    $harga       = $_POST['harga'];
    $qty         = $_POST['qty'];
    $bayar       = (int) $_POST['bayar'];

    $total = 0;
    for ($i = 0; $i < count($kode_barang); $i++) {
        $total += (int)$harga[$i] * (int)$qty[$i];
    }

    if ($bayar < $total) {
        $fmt_total = number_format($total, 0, ',', '.');
        $fmt_bayar = number_format($bayar, 0, ',', '.');
        echo "<script>alert('Uang bayar kurang! Total: Rp $fmt_total, Bayar: Rp $fmt_bayar'); history.back();</script>";
        exit;
    }

    $kembalian = $bayar - $total;

    $queryHeader = mysqli_query($connection, "
        INSERT INTO tbl_penjualan
        VALUES (NULL, '$kode_transaksi', '$tanggal', '$total', '$bayar', '$kembalian', '$user')
    ");
    if (!$queryHeader) {
        die('Error Header: ' . mysqli_error($connection));
    }

    for ($i = 0; $i < count($kode_barang); $i++) {
        $sub        = (int)$harga[$i] * (int)$qty[$i];
        $kd         = mysqli_real_escape_string($connection, $kode_barang[$i]);
        $nm         = mysqli_real_escape_string($connection, $nama_barang[$i]);
        $hr         = (int)$harga[$i];
        $qt         = (int)$qty[$i];
        $kodeDetail = 'DTL' . time() . $i;

        $queryDetail = mysqli_query($connection, "
            INSERT INTO tbl_detail_penjualan
            VALUES (NULL, '$kodeDetail', '$kode_transaksi', '$kd', '$nm', '$hr', '$qt', '$sub')
        ");
        if (!$queryDetail) {
            die('Error Detail ke-' . ($i + 1) . ': ' . mysqli_error($connection));
        }
    }

    $fmt_kembalian = number_format($kembalian, 0, ',', '.');
    $_SESSION['pos_success'] = "Transaksi berhasil! Kembalian: Rp $fmt_kembalian";
    echo "<script>window.location='index.php?halaman=data_penjualan';</script>";
    exit;
}
?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tambah Transaksi Penjualan</h3>
                <p class="text-subtitle text-muted">Buat transaksi kasir baru</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?halaman=data_penjualan">Penjualan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah Transaksi</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <form method="post" id="formPenjualan">

                    <div class="table-responsive mb-3">
                        <table class="table table-bordered" id="tableBarang">
                            <thead class="table-light">
                                <tr>
                                    <th>Cari Barang</th>
                                    <th style="width:200px">Nama Barang</th>
                                    <th style="width:150px">Harga Satuan</th>
                                    <th style="width:110px">Qty</th>
                                    <th style="width:160px">Subtotal</th>
                                    <th style="width:80px">Hapus</th>
                                </tr>
                            </thead>
                            <tbody id="bodyBarang"></tbody>
                        </table>
                    </div>

                    <button type="button" onclick="tambahBaris()" class="btn btn-success mb-4">
                        <i class="bi bi-plus-circle"></i> Tambah Barang
                    </button>

                    <div class="row justify-content-end">
                        <div class="col-md-5">
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Total Belanja</strong></td>
                                    <td class="text-end">
                                        <strong id="displayTotal">Rp 0</strong>
                                        <input type="hidden" name="total_display" id="totalInput" value="0">
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="bayar"><strong>Uang Bayar</strong></label></td>
                                    <td class="text-end">
                                        <input type="number" name="bayar" id="bayar" class="form-control text-end"
                                            placeholder="Masukkan nominal" min="0" required oninput="hitungKembalian()">
                                    </td>
                                </tr>
                                <tr id="rowKembalian" style="display:none">
                                    <td><strong>Kembalian</strong></td>
                                    <td class="text-end">
                                        <strong id="displayKembalian" class="text-success">Rp 0</strong>
                                    </td>
                                </tr>
                                <tr id="rowKurang" style="display:none">
                                    <td colspan="2">
                                        <div class="alert alert-danger py-1 mb-0 text-center">
                                            &#9888; Uang bayar tidak cukup!
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <button type="submit" name="submit" id="btnSimpan" class="btn btn-primary" disabled>
                        <i class="bi bi-save"></i> Simpan Transaksi
                    </button>
                    <a href="index.php?halaman=data_penjualan" class="btn btn-secondary ms-2">Batal</a>

                </form>
            </div>
        </div>
    </section>
</div>

<?php
// Output JS & CSS via echo agar tidak ada masalah encoding/parsing
$js = <<<JS
<style>
.ac-list{position:absolute;top:100%;left:0;right:0;z-index:999;background:#fff;border:1px solid #dee2e6;border-top:none;border-radius:0 0 6px 6px;box-shadow:0 4px 12px rgba(0,0,0,.12);max-height:240px;overflow-y:auto;}
.ac-item{display:flex;justify-content:space-between;align-items:center;padding:8px 12px;cursor:pointer;font-size:.9rem;transition:background .15s;}
.ac-item:hover,.ac-item.active{background:#f0f4ff;}
.ac-nama{font-weight:500;color:#333;}
.ac-harga{font-size:.82rem;color:#28a745;font-weight:600;}
</style>
<script>
const dataBarang = $listBarangJson;

function formatRupiah(n){return 'Rp '+Number(n).toLocaleString('id-ID');}

function hitungTotal(){
    let total=0;
    document.querySelectorAll('.row-barang').forEach(function(row){
        var h=parseInt(row.querySelector('.inp-harga').value)||0;
        var q=parseInt(row.querySelector('.inp-qty').value)||0;
        var s=h*q;
        row.querySelector('.td-subtotal').textContent=formatRupiah(s);
        total+=s;
    });
    document.getElementById('displayTotal').textContent=formatRupiah(total);
    document.getElementById('totalInput').value=total;
    hitungKembalian();
}

function hitungKembalian(){
    var total=parseInt(document.getElementById('totalInput').value)||0;
    var bayar=parseInt(document.getElementById('bayar').value)||0;
    var kembalian=bayar-total;
    var rK=document.getElementById('rowKembalian');
    var rX=document.getElementById('rowKurang');
    var btn=document.getElementById('btnSimpan');
    if(bayar<=0||total===0){rK.style.display='none';rX.style.display='none';btn.disabled=true;return;}
    if(kembalian<0){rK.style.display='none';rX.style.display='';btn.disabled=true;}
    else{rX.style.display='none';rK.style.display='';document.getElementById('displayKembalian').textContent=formatRupiah(kembalian);btn.disabled=false;}
}

function initAutocomplete(inputEl){
    var wrapper=inputEl.closest('.ac-wrapper');
    var list=wrapper.querySelector('.ac-list');
    var row=inputEl.closest('tr');

    inputEl.addEventListener('input',function(){
        var keyword=this.value.trim().toLowerCase();
        list.innerHTML='';
        if(keyword.length===0){list.style.display='none';return;}
        var hasil=dataBarang.filter(function(b){
            return b.nama_barang.toLowerCase().includes(keyword)||b.kode_barang.toLowerCase().includes(keyword);
        }).slice(0,8);
        if(hasil.length===0){list.style.display='none';return;}
        hasil.forEach(function(b){
            var item=document.createElement('div');
            item.className='ac-item';
            item.innerHTML='<span class="ac-nama">'+b.nama_barang+'</span><span class="ac-harga">'+formatRupiah(b.harga_jual)+'</span>';
            item.addEventListener('mousedown',function(e){
                e.preventDefault();
                pilihBarang(row,b);
                inputEl.value=b.nama_barang;
                list.style.display='none';
            });
            list.appendChild(item);
        });
        list.style.display='block';
    });

    inputEl.addEventListener('blur',function(){setTimeout(function(){list.style.display='none';},150);});

    inputEl.addEventListener('keydown',function(e){
        var items=list.querySelectorAll('.ac-item');
        var active=list.querySelector('.ac-item.active');
        if(e.key==='ArrowDown'){e.preventDefault();if(!active){if(items[0])items[0].classList.add('active');}else{active.classList.remove('active');var next=active.nextElementSibling||items[0];if(next)next.classList.add('active');}}
        else if(e.key==='ArrowUp'){e.preventDefault();if(!active){if(items[items.length-1])items[items.length-1].classList.add('active');}else{active.classList.remove('active');var prev=active.previousElementSibling||items[items.length-1];if(prev)prev.classList.add('active');}}
        else if(e.key==='Enter'){e.preventDefault();if(active)active.dispatchEvent(new Event('mousedown'));}
        else if(e.key==='Escape'){list.style.display='none';}
    });
}

function pilihBarang(row,b){
    row.querySelector('.inp-kode').value=b.kode_barang;
    row.querySelector('.inp-nama').value=b.nama_barang;
    row.querySelector('.inp-harga').value=b.harga_jual;
    hitungTotal();
}

function buatRow(){
    var tr=document.createElement('tr');
    tr.className='row-barang';
    tr.innerHTML='<td><div class="ac-wrapper" style="position:relative"><input type="text" class="form-control inp-cari" placeholder="Ketik nama / kode barang..." autocomplete="off"><div class="ac-list" style="display:none"></div></div><input type="hidden" name="kode_barang[]" class="inp-kode"></td><td><input type="text" name="nama_barang[]" class="form-control inp-nama" readonly placeholder="-"></td><td><input type="number" name="harga[]" class="form-control inp-harga text-end" readonly placeholder="0" value="0"></td><td><input type="number" name="qty[]" class="form-control inp-qty text-end" placeholder="1" min="1" value="1" oninput="hitungTotal()" required></td><td class="td-subtotal text-end align-middle">Rp 0</td><td class="text-center align-middle"><button type="button" class="btn btn-danger btn-sm" onclick="hapusBaris(this)"><i class="bi bi-trash"></i></button></td>';
    initAutocomplete(tr.querySelector('.inp-cari'));
    return tr;
}

function tambahBaris(){document.getElementById('bodyBarang').appendChild(buatRow());}

function hapusBaris(btn){
    if(document.querySelectorAll('.row-barang').length<=1){alert('Minimal harus ada 1 barang!');return;}
    btn.closest('tr').remove();
    hitungTotal();
}

document.addEventListener('DOMContentLoaded',function(){tambahBaris();});
</script>
JS;
echo $js;
?>