
import Swal from 'sweetalert2';
import './bootstrap';

// Exemple : configuration par défaut (facultatif)
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
});
export default Toast;

