$(document).ready(function() {
    $("#tambah_ikan").click(function(e) {
		e.preventDefault();
		var formDataUpload = $('#formDataUpload');
		var formAction = $('#formDataUpload').data('url');
		if (formDataUpload.length != 0) {
			var kode_penjualan = $("#kode_penjualan").val();
			var id_nelayan = $("#nelayan").val();
			var id_ikan = $("#ikan").val();
			// var nama_ikan = $("#ikan").getAttribute('data-nama_ikan');
			var jumlah = $("#jumlah").val();
			var harga_ikan = $("#harga_ikan").val();
			var active = document.getElementById("ikan");
			var nama_ikan = active.options[active.selectedIndex].getAttribute('data-nama_ikan');
			
			var formData = new FormData();
			
			formData.append("kode_penjualan", kode_penjualan);
			formData.append("nelayan", id_nelayan);
			formData.append("ikan", id_ikan);
			formData.append("nama_ikan", nama_ikan);
			formData.append("jumlah", jumlah);
			formData.append("harga_ikan", harga_ikan);
			
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
						document.getElementById("ikan").value = '';
						document.getElementById("jumlah").value = '';
						// document.getElementById("harga_ikan").value = '';
						
						var tablePreview = $("#ikan_form_input tbody");
						var strContent;
						tablePreview.empty();
						for (let i = 0; i < res.length; i++) {
							fungsi2 = "delete_detail("+res[i].kode_penjualan+","+res[i].nelayan +","+ res[i].ikan +","+ res[i].nama_ikan +","+ res[i].jumlah +","+ res[i].harga_ikan +","+ res[i].total+")";
							// alert(fungsi2);
							strContent = "<tr>";
							strContent = strContent + "<td align='center'>" + res[i].nama_ikan + "</td>";
							strContent = strContent + "<td align='center'>" + res[i].jumlah + "</td>";
							strContent = strContent + "<td align='right'>" + formatRupiah(res[i].harga_ikan, "Rp. ") + "</td>";
							strContent = strContent + "<td align='right'>" + formatRupiah(res[i].total, "Rp. ") + "</td>";
							strContent = strContent + "<td align='center'>";
							strContent = strContent + '<a class="tombol-hapus" name="detail_data" data-kode_penjualan="'+res[i].kode_penjualan+'" data-nelayan="'+res[i].nelayan+'" data-ikan="'+res[i].ikan+'" data-nama_ikan="'+res[i].nama_ikan+'" data-jumlah="'+res[i].jumlah+'" data-harga_ikan="'+res[i].harga_ikan+'" data-total="'+res[i].total+'" href ="javascript:;" onclick="'+fungsi2+'"  style="color : blue;"><i class="fas fa-trash"></i></a>';

							// strContent = strContent + '<a class="tombol-hapus" href ="javascript:;"  style="color : blue;" onclick="'+fungsi2+'"><i class="fas fa-trash"></i></a>';
							strContent = strContent + "</td>";
							strContent = strContent + "</tr>";							
							tablePreview.append(strContent);
						}
						document.getElementById("nelayan").setAttribute("disabled", "disabled");
						alert("Berhasil Tambah Ikan!\nKlik Finish Untuk Lanjut Pembayaran");
					}
				}
			});
		}
	});

	$('body').on('click', '.tombol-hapus', function(e) {
		var mdl = $(this);
		var kode_penjualan = mdl.data('kode_penjualan');
		var nelayan = mdl.data('nelayan');
		var ikan = mdl.data('ikan');
		var nama_ikan = mdl.data('nama_ikan');
		var jumlah = mdl.data('jumlah');
		var harga_ikan = mdl.data('harga_ikan');
		var total = mdl.data('total');
		delete_detail(kode_penjualan,nelayan,ikan,nama_ikan,jumlah,harga_ikan,total);
	});
});

function delete_detail(kode_penjualan,nelayan,ikan,nama_ikan,jumlah,harga_ikan,total){
	if(confirm('Are you sure?'))
	{
		var uri = document.baseURI;
		var nexturi = uri.replace("form_penjualan", "hapus_ikan");
		var nexturi1 = nexturi.replace("#", "");
		var urladaw = nexturi1+'/'+kode_penjualan+'/'+nelayan+'/'+ikan+'/'+nama_ikan+'/'+jumlah+'/'+harga_ikan+'/'+total;

		// alert(kode_penjualan);
		$.ajax({
			url: urladaw,
			async: false,
			cache: false,
			data: {
				kode_penjualan:kode_penjualan,
				nelayan:nelayan,
				ikan:ikan,
				nama_ikan:nama_ikan,
				jumlah:jumlah,
				harga_ikan:harga_ikan,
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
				// alert('a');
				document.getElementById("ikan").value = '';
				document.getElementById("jumlah").value = '';
				// document.getElementById("harga_ikan").value = '';
				
				var tablePreview = $("#ikan_form_input tbody");
				var strContent;
				tablePreview.empty();
				for (let i = 0; i < res.length; i++) {
					fungsi2 = "delete_detail("+res[i].kode_penjualan+","+res[i].nelayan +","+ res[i].ikan +","+ res[i].nama_ikan +","+ res[i].jumlah +","+ res[i].harga_ikan +","+ res[i].total+")";
					strContent = "<tr>";
					strContent = strContent + "<td align='center'>" + res[i].nama_ikan + "</td>";
					strContent = strContent + "<td align='center'>" + res[i].jumlah + "</td>";
					strContent = strContent + "<td align='right'>" + formatRupiah(res[i].harga_ikan, "Rp. ") + "</td>";
					strContent = strContent + "<td align='right'>" + formatRupiah(res[i].total, "Rp. ") + "</td>";
					strContent = strContent + "<td align='center'>";
					// strContent = strContent + '<a class="tombol-hapus" name="detail_data" data-kode_penjualan="'+res[i].kode_penjualan+'" href ="javascript:;"  style="color : blue;" onclick="'+fungsi2+'"><i class="fas fa-trash"></i></a>';
					strContent = strContent + '<a class="tombol-hapus" name="detail_data" data-kode_penjualan="'+res[i].kode_penjualan+'" data-nelayan="'+res[i].nelayan+'" data-ikan="'+res[i].ikan+'" data-nama_ikan="'+res[i].nama_ikan+'" data-jumlah="'+res[i].jumlah+'" data-harga_ikan="'+res[i].harga_ikan+'" data-total="'+res[i].total+'" href ="javascript:;" onclick="'+fungsi2+'"  style="color : blue;"><i class="fas fa-trash"></i></a>';
					strContent = strContent + "</td>";
					strContent = strContent + "</tr>";							
					tablePreview.append(strContent);
				}
				document.getElementById("nelayan").setAttribute("disabled", "disabled");
				alert("Berhasil Hapus Ikan!\nKlik Finish Untuk Lanjut Pembayaran");          
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
	// var x = document.getElementById("item_sku").value;
	var active = document.getElementById("ikan");
	var harga_ikan = active.options[active.selectedIndex].getAttribute('data-harga');
	// alert(harga_ikan);

	document.getElementById("harga_ikan").setAttribute('value', formatrupiah(harga_ikan, "Rp. "));
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