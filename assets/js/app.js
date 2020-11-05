import "@glidejs/glide/dist/css/glide.core.css"
import "@glidejs/glide/dist/css/glide.theme.css"
import "bootstrap";
import "bootstrap/dist/css/bootstrap.min.css";
import Glide from '@glidejs/glide/dist/glide.modular.esm';
import '../styles/app.css';

const slides = document.querySelectorAll('.glide')
const conf = {
    type:'carousel',
    bound: true,
    gap: 0,
    perView:1,
    focusAt: 'center',
    autoplay: 1000
}

slides.forEach(item => {
   new Glide(item, conf).mount()
})



