import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

function successToast(message) {
    toast(message, {
        theme: "auto",
        type: "success",
        position: "bottom-right",
        transition: "slide",
        hideProgressBar: true,
        hideProgressBar: true,
        dangerouslyHTMLString: true
    })
}

function errorToast(message) {
    toast(message, {
        theme: "auto",
        type: "error",
        position: "bottom-right",
        transition: "slide",
        hideProgressBar: true,
        dangerouslyHTMLString: true,  
    });
}

export default{successToast,errorToast} ;
