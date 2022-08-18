const flashData = $('.flash-data').data('flashdata');
const flashData2 = $('.flash-data2').data('flashdata');
const flashData3 = $('.flash-data3').data('flashdata');
const flashData4 = $('.flash-data4').data('flashdata');
const flashData5 = $('.flash-data5').data('flashdata');
const flashData6 = $('.flash-data6').data('flashdata');
const flashData7 = $('.flash-data7').data('flashdata');

if (flashData) {
    Swal.fire({
        position: 'center',
        type: 'success',
        title: 'Data Berhasil ' + flashData,
        showConfirmButton: false,
        timer: 1500
    })
}

if (flashData2) {
    Swal.fire({
        type: 'error',
        title: 'Oops...',
        text: flashData2
    })
}

if (flashData4) {
    Swal.fire({
        position: 'center',
        type: 'error',
        title: flashData4,
        showConfirmButton: false,
        timer: 5000
    })
}

if (flashData5) {
    Swal.fire({
        position: 'center',
        type: 'success',
        title: flashData5,
        showConfirmButton: false,
        timer: 800
    })
}

if (flashData6) {
    Swal.fire({
        position: 'center',
        type: 'success',
        title: flashData6,
        showConfirmButton: false,
        timer: 800
    })
}

if (flashData7) {
    Swal.fire({
        position: 'center',
        type: 'success',
        title: flashData7,
        showConfirmButton: false,
        timer: 800
    })
}

$(function() {
    var Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
    });
    $('.swalDefaultError').click(function() {
        Toast.fire({
        icon: 'error',
        title: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
        })
    });
});