$(document).ready(function() {
    $("#tambah_alat_bahan").click(function(e) {
		e.preventDefault();
		var formDataUpload = $('#formDataUpload');
		var formAction = $('#formDataUpload').data('url');
		if (formDataUpload.length != 0) {
			var kode_peminjaman = $("#kode_peminjaman").val();
			var id_nelayan = $("#nelayan").val();
			var alat_bahan = $("#alat_bahan").val();
			var active = document.getElementById("alat_bahan");
			var nama_alat_bahan = active.options[active.selectedIndex].getAttribute('data-nama_alat_bahan');
			var jumlah = $("#jumlah").val();
			var harga_alat_bahan = $("#harga_alat_bahan").val();
			
			var formData = new FormData();
			
			formData.append("kode_peminjaman", kode_peminjaman);
			formData.append("id_nelayan", id_nelayan);
			formData.append("alat_bahan", alat_bahan);
			formData.append("nama_alat_bahan", nama_alat_bahan);
			formData.append("jumlah", jumlah);
			formData.append("harga_alat_bahan", harga_alat_bahan);
			
			$.ajax({
				url: formAction,
				async: false,
				cache: false,
				data: formData,
				type: 'post',
				processData: false,
				contentType: false,
				dataType: 'json',
				beforeSend: function() {
					$('.preloader').fadeIn();
				},
				success: function(res) {

					if(res.kode == 'Error'){
						alert(res.message);
					}else{
						document.getElementById("alat_bahan").value = '';
						document.getElementById("jumlah").value = '';
						
						var tablePreview = $("#alat_bahan_form_input tbody");
						var strContent;
						tablePreview.empty();
						for (let i = 0; i < res.length; i++) {
							fungsi2 = "delete_detail("+res[i].kode_peminjaman+","+res[i].id_nelayan +","+ res[i].alat_bahan +","+ res[i].nama_alat_bahan +","+ res[i].jumlah +","+ res[i].harga_alat_bahan +","+ res[i].total+")";
							// alert(fungsi2);
							strContent = "<tr>";
							strContent = strContent + "<td align='center'>" + res[i].nama_alat_bahan + "</td>";
							strContent = strContent + "<td align='center'>" + res[i].jumlah + "</td>";
							strContent = strContent + "<td align='right'>" + formatRupiah(res[i].harga_alat_bahan, "Rp. ") + "</td>";
							strContent = strContent + "<td align='right'>" + formatRupiah(res[i].total, "Rp. ") + "</td>";
							strContent = strContent + "<td align='center'>";
							strContent = strContent + '<a class="tombol-hapus" name="detail_data" data-kode_peminjaman="'+res[i].kode_peminjaman+'" data-id_nelayan="'+res[i].id_nelayan+'" data-alat_bahan="'+res[i].alat_bahan+'" data-nama_alat_bahan="'+res[i].nama_alat_bahan+'" data-jumlah="'+res[i].jumlah+'" data-harga_alat_bahan="'+res[i].harga_alat_bahan+'" data-total="'+res[i].total+'" href ="javascript:;" onclick="'+fungsi2+'"  style="color : blue;"><i class="fas fa-trash"></i></a>';
							strContent = strContent + "</td>";
							strContent = strContent + "</tr>";							
							tablePreview.append(strContent);
						}
						document.getElementById("nelayan").setAttribute("disabled", "disabled");
						alert("Berhasil Tambah Alat/Bahan!\nKlik Finish Untuk Lanjut Pembayaran");
					}
				}
			});
		}
	});

	$('body').on('click', '.tombol-hapus', function(e) {
		var mdl = $(this);
		var kode_peminjaman = mdl.data('kode_peminjaman');
		var id_nelayan = mdl.data('id_nelayan');
		var alat_bahan = mdl.data('alat_bahan');
		var nama_alat_bahan = mdl.data('nama_alat_bahan');
		var jumlah = mdl.data('jumlah');
		var harga_alat_bahan = mdl.data('harga_alat_bahan');
		var total = mdl.data('total');
		delete_detail(kode_peminjaman,id_nelayan,alat_bahan,nama_alat_bahan,jumlah,harga_alat_bahan,total);
	});
});

function delete_detail(kode_peminjaman,id_nelayan,alat_bahan,nama_alat_bahan,jumlah,harga_alat_bahan,total){
	if(confirm('Are you sure?'))
	{
		var uri = document.baseURI;
		var nexturi = uri.replace("form_peminjaman", "hapus_alat_bahan");
		var nexturi1 = nexturi.replace("#", "");
		var urladaw = nexturi1+'/'+kode_peminjaman+'/'+id_nelayan+'/'+alat_bahan+'/'+nama_alat_bahan+'/'+jumlah+'/'+harga_alat_bahan+'/'+total;

		// alert(kode_penjualan);
		$.ajax({
			url: urladaw,
			async: false,
			cache: false,
			data: {
				kode_peminjaman:kode_peminjaman,
				id_nelayan:id_nelayan,
				alat_bahan:alat_bahan,
				nama_alat_bahan:nama_alat_bahan,
				jumlah:jumlah,
				harga_alat_bahan:harga_alat_bahan,
				total:total
			},
			type: 'POST',
			processData: false,
			contentType: false,
			dataType: 'json',
			beforeSend: function() {
				$('.preloader').fadeIn();
			},
			success: function(res) {
				document.getElementById("alat_bahan").value = '';
				document.getElementById("jumlah").value = '';
				
				var tablePreview = $("#alat_bahan_form_input tbody");
				var strContent;
				tablePreview.empty();
				for (let i = 0; i < res.length; i++) {
					fungsi2 = "delete_detail("+res[i].kode_peminjaman+","+res[i].id_nelayan +","+ res[i].alat_bahan +","+ res[i].nama_alat_bahan +","+ res[i].jumlah +","+ res[i].harga_alat_bahan +","+ res[i].total+")";
					strContent = "<tr>";
					strContent = strContent + "<td align='center'>" + res[i].nama_alat_bahan + "</td>";
					strContent = strContent + "<td align='center'>" + res[i].jumlah + "</td>";
					strContent = strContent + "<td align='right'>" + formatRupiah(res[i].harga_alat_bahan, "Rp. ") + "</td>";
					strContent = strContent + "<td align='right'>" + formatRupiah(res[i].total, "Rp. ") + "</td>";
					strContent = strContent + "<td align='center'>";
					strContent = strContent + '<a class="tombol-hapus" name="detail_data" data-kode_peminjaman="'+res[i].kode_peminjaman+'" data-id_nelayan="'+res[i].id_nelayan+'" data-alat_bahan="'+res[i].alat_bahan+'" data-nama_alat_bahan="'+res[i].nama_alat_bahan+'" data-jumlah="'+res[i].jumlah+'" data-harga_alat_bahan="'+res[i].harga_alat_bahan+'" data-total="'+res[i].total+'" href ="javascript:;" onclick="'+fungsi2+'"  style="color : blue;"><i class="fas fa-trash"></i></a>';
					strContent = strContent + "</td>";
					strContent = strContent + "</tr>";							
					tablePreview.append(strContent);
				}
				document.getElementById("nelayan").setAttribute("disabled", "disabled");
				alert("Berhasil Hapus Alat/Bahan!\nKlik Finish Untuk Lanjut Pembayaran");        
			}
		});
	}
}

function hanyaAngka(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

function ganti_harga() {
	var active = document.getElementById("alat_bahan");
	var harga_alat_bahan = active.options[active.selectedIndex].getAttribute('data-harga_per_unit');

	document.getElementById("harga_alat_bahan").setAttribute('value', formatrupiah(harga_alat_bahan, "Rp. "));
}

/* Fungsi formatRupiah */
function formatRupiah(angka, prefix) {
	var number_string = angka.toString().replace(/[^,\d]/g, ''),
		split = number_string.split(','),
		sisa = split[0].length % 3,
		rupiah = split[0].substr(0, sisa),
		ribuan = split[0].substr(sisa).match(/\d{3}/gi);

	if (ribuan) {
		separator = sisa ? '.' : '';
		rupiah += separator + ribuan.join('.');
	}

	rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
	return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}

function formatrupiah(angka, prefix) {
	var number_string = angka.replace(/[^,\d]/g, '').toString(),
		split = number_string.split(','),
		sisa = split[0].length % 3,
		rupiah = split[0].substr(0, sisa),
		ribuan = split[0].substr(sisa).match(/\d{3}/gi);

	if (ribuan) {
		separator = sisa ? '.' : '';
		rupiah += separator + ribuan.join('.');
	}

	rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
	return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}