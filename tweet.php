<?php

if (isset($_POST['posting'])) {
    $content = $_POST['content'];


    // JIKA GAMBAR MAU DI UBAH
    if (!empty($_FILES['foto']['name'])) {
        $nama_foto = $_FILES['foto']['name'];
        $ukuran_foto = $_FILES['foto']['size'];

        // png, jpg, jpeg
        $ext = array('png', 'jpg', 'jpeg');
        $extFoto = pathinfo($nama_foto, PATHINFO_EXTENSION);

        // JIKA EXTENSI FOTO TIDAK ADA EXT YANG TERDAFTAR DI ARRAY EXT
        if (!in_array($extFoto, $ext)) {
            echo "Extension tidak ditemukan";
            die;
        } else {
            // pindahkan gambar dari tmp folder ke folder yang sudah kita buat
            // unlink() : mendelete file
            unlink('upload/' . $rowTweet['foto']);
            move_uploaded_file($_FILES['foto']['tmp_name'], 'upload/' . $nama_foto);

            $insert = mysqli_query($koneksi, "INSERT INTO tweet (content, foto, id_user) VALUES ('$content','$nama_foto','$id_user')");
        }
    } else {
        // GAMBAR TIDAK MAU DI UBAH
        $insert = mysqli_query($koneksi, "INSERT INTO tweet (content, id_user) VALUES ('$content','$id_user')");
    }
    header('location:?pg=profil&tweet=berhasil');
}

$queryPosting = mysqli_query($koneksi, "SELECT tweet. * FROM tweet WHERE id_user = '$id_user'");
// $resultPosting = mysqli_fetch_assoc($queryPosting);
// print_r($resultPosting);
// die;

?>

<div class="row">
    <div class="col-sm-12" align="right">
        <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#exampleModal1">Tweet</button>
    </div>
    <div class="col-sm-12 mt-3">
        <?php while ($rowPosting = mysqli_fetch_array($queryPosting)): ?>
            <div class="d-flex">
                <div class="flex-shrink-0">
                    <img src="upload/<?php echo !empty($rowUser['foto']) ? $rowUser['foto'] : 'https://placehold.co/800x200' ?>"
                        alt="..." class="rounded-circle border border-2" height="50" width="50">
                </div>
                <div class="flex-grow-1 ms-3">
                    <?php if (!empty($rowPosting['foto'])): ?>
                        <img class="rounded-" src="upload/<?php echo $rowPosting['foto'] ?>" alt="">
                    <?php endif ?>
                    <?php echo $rowPosting['content']; ?>
                </div>
            </div>
            <div class="flex-grow-1 ms-3">
                <form action="add_comment.php" method="POST">
                    <input type="text" name="status_id" value="<?php echo $rowPosting['id'] ?>">
                    <input type="text" name="user_id" value="<?php echo $rowPosting['id_user'] ?>">
                    <textarea class="form-control" name="comment_text" id="comment_text" cols="5" rows="5"
                        placeholder="Tuliskan Komentar..."></textarea>
                    <button class="btn btn-primary btn-sm mt-2" type="submit">Kirim Balasan</button>
                </form>

                <!-- Komentar -->
                <div class="mt-2 alert" id="comment-alert" style="display: none;"></div>
                <div class="mt-1">
                    <?php
                    if (isset($rowPosting['id']) && $rowPosting['id_user']) {
                        $idStatus = $rowPosting['id'];
                        $userId = $rowPosting['id_user'];
                        $queryComment = mysqli_query($koneksi, "SELECT * FROM comments WHERE status_id='$idStatus' AND user_id='$userId'");
                        $rowCounts = mysqli_fetch_all($queryComment, MYSQLI_ASSOC);
                        foreach ($rowCounts as $rowCount) {
                    ?>
                            <span>
                                <pre>Comment : <?php echo $rowCount['comment_text'] ?></pre>
                            </span>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
    </div>
<?php
        endwhile
?>
</div>
<hr>
</div>

<!-- MODAL TWEET -->
<div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Tweet</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <textarea name="content" id="summernote"
                            class="form-control">Apa Yang Sedang Anda Pikirkan?</textarea>
                    </div>
                    <div class="mb-3">
                        <input type="file" name="foto">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" name="posting" class="btn btn-primary">Tweet</button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- <script>
    document.getElementById('comment-form').addEventListener('submit', function(e));
    {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('add_comments.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
        const alertBox = document.getElementById('comment-alert');
        if (data.status === "success") {
            alertBox.className = "alert alert-success";
            alertBox.innerHTML = data.message;
            // bersihkan textarea
            document.getElementById('comment_text').value = "";
            location.reload();
        } else {
            alertBox.className = "alert alert-danger";
            alertBox.innerHTML = data.message;
        }
        alertBox.style.display = "block";
    })
    .catch(error => console.error("Error:", error)({

});
</script> -->