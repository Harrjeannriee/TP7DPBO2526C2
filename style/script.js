// mengambil elemen tombol untuk membuka dan menutup sidebar
const toggleButton = document.getElementById("toggleSidebar");
// mengambil elemen sidebar dari halaman html
const sidebar = document.getElementById("sidebar");
// menambahkan event listener untuk mendeteksi klik pada tombol
toggleButton.addEventListener("click", function () {
    // menambah/menghapus class "collapsed" untuk mengubah kondisi tampilan sidebar
    sidebar.classList.toggle("collapsed");
});
