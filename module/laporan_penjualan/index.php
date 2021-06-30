<h3>Laporan Penjualan</h3>

<div id="content-data">
    <!-- <form> -->
        <div class="row">
            <div class="form-group col-md-6">
                <label for="jenis_lap">Jenis Laporan</label>
                <select name="jenis_lap" id="jenis_lap" class="form-control">
                    <option value="harian">Harian</option>
                    <option value="bulanan">Bulanan</option>
                    <option value="tahunan">Tahunan</option>
                </select>
            </div>

            <div class="form-group col-md-6" id="tanggal-field">
                <label for="tanggal">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control">
                <small id="validate-tanggal" class="text-danger"></small>
            </div>

            <div class="form-group col-md-6" id="bulan-field" style="display: none;">
                <label for="bulan">Bulan</label>
                <input type="month" name="bulan" id="bulan" class="form-control">
                <small id="validate-bulan" class="text-danger"></small>
            </div>

            <div class="form-group col-md-6" id="tahun-field" style="display: none;">
                <label for="tahun">Tahun</label>
                <select name="tahun" id="tahun" class="form-control">
                    <?php for ($i=2021; $i > 2018; $i--) {
                        echo "<option value=".$i.">".$i."</option>";
                    } ?>
                </select>
            </div>
        </div>

        <button id="laporan-preview" class="btn btn-secondary">Lihat</button>
        <button id="laporan-cetak" class="btn btn-primary">Cetak</button>

        <div id="preview-content"></div>

    <!-- </form> -->
</div>


<script>
    const jenisLaporan  = document.getElementById('jenis_lap');
    const tanggalField  = document.getElementById('tanggal-field');
    const bulanField    = document.getElementById('bulan-field');
    const tahunField    = document.getElementById('tahun-field');
    const tanggal  = document.getElementById('tanggal');
    const bulan    = document.getElementById('bulan');
    const tahun    = document.getElementById('tahun');
    const laporanPreviewBtn = document.getElementById('laporan-preview');
    const laporanCetakBtn   = document.getElementById('laporan-cetak');
    const previewContent    = document.getElementById('preview-content');

    const validateTanggal = document.getElementById('validate-tanggal');
    const validateBulan = document.getElementById('validate-bulan');


    jenisLaporan.addEventListener('change', function(e){
        const _jenisLaporan = e.target.value;

        if (_jenisLaporan === 'bulanan') {
            tanggalField.style.display = 'none';
            bulanField.style.display = 'block';
            tahunField.style.display = 'none';
        } else if (_jenisLaporan === 'tahunan' ) {
            tanggalField.style.display = 'none';
            bulanField.style.display = 'none';
            tahunField.style.display = 'block';
        } else {
            tanggalField.style.display = 'block';
            bulanField.style.display = 'none';
            tahunField.style.display = 'none';
        }
    })

    laporanPreviewBtn.addEventListener('click', function(){
        const _jenisLaporan = jenisLaporan.value;

        if (_jenisLaporan === 'tahunan') {
            // get report
                $.ajax({
                    type	: "GET",
                    url		: "actions.php?action=preview_lap_penjualan&laporan="+_jenisLaporan+"&tahun="+tahun.value,
                    success	: function(res){
                        previewContent.innerHTML = res
                    }
                });
        } else if (_jenisLaporan === 'bulanan' ) {
            if (bulan.value) {
                validateBulan.textContent = '';
                // get report
                $.ajax({
                    type	: "GET",
                    url		: "actions.php?action=preview_lap_penjualan&laporan="+_jenisLaporan+"&bulan="+bulan.value,
                    success	: function(res){
                        previewContent.innerHTML = res
                    }
                });
            } else {
                validateBulan.textContent = 'Bulan dan tahun harus dipilih';
            }
        } else {
            if (tanggal.value) {
                validateTanggal.textContent = '';
                // get report
                $.ajax({
                    type	: "GET",
                    url		: "actions.php?action=preview_lap_penjualan&laporan="+_jenisLaporan+"&tanggal="+tanggal.value,
                    success	: function(res){
                        previewContent.innerHTML = res
                    }
                });
            } else {
                validateTanggal.textContent = 'Tanggal harus dipilih';
            }
        }
    })

    laporanCetakBtn.addEventListener('click', function(){
        console.log('cetak laporan...');
        const _jenisLaporan = jenisLaporan.value;

        if (_jenisLaporan === 'tahunan') {
            window.location.href = "module/laporan_penjualan/report?laporan="+_jenisLaporan+"&tahun="+tahun.value;
        } else if (_jenisLaporan === 'bulanan' ) {
            if (bulan.value) {
                validateBulan.textContent = '';
                window.location.href = "module/laporan_penjualan/report?laporan="+_jenisLaporan+"&bulan="+bulan.value;
            } else {
                validateBulan.textContent = 'Bulan dan tahun harus dipilih';
            }
        } else {
            if (tanggal.value) {
                validateTanggal.textContent = '';
                window.location.href = "module/laporan_penjualan/report?laporan="+_jenisLaporan+"&tanggal="+tanggal.value;
            } else {
                validateTanggal.textContent = 'Tanggal harus dipilih';
            }
        }
    })

</script>