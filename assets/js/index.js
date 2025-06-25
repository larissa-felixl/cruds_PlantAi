function mostra_img(event) {
    document.getElementById('preview').src = event.target.result;
    document.getElementById('preview').style.display = 'block';
        
    document.querySelector('#upload-area > p').style.opacity = '0';
    document.querySelector('#img_salva:first-of-type').style.opacity = '0';
}

function ler_img() {
    const arquivo = this.files[0];

    if (arquivo) {
        const leitor = new FileReader();
        leitor.onload = mostra_img;
        leitor.readAsDataURL(arquivo);
    } else {
        document.getElementById('preview').style.display = 'none';
        document.getElementById('preview').src = '#';
    }
}

document.getElementById('image').addEventListener('change', ler_img);