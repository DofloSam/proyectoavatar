$("#inputImg").change(function(e) {
    const file = e.target.files[0];
    if (file) {
        const img = URL.createObjectURL(file);
        $("#imgPreview").attr("src", img).show();
    }
});