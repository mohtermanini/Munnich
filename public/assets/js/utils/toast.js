export class ToastUtils {
  static defaultOptions = {
    toast: true,
    position: "top-right",
    showConfirmButton: false,
    timer: 5000,
    timerProgressBar: true,
    showClass: {
      popup: '', // No animation on show
    },
    hideClass: {
      popup: '', // No animation on hide
    },
  };

  static showError(message, title = "Error") {
    Swal.fire({
      ...this.defaultOptions,
      title: title,
      text: message,
      icon: "error",
      customClass: {
        popup: "swal-toast-error",
      },
    });
  }

  static showSuccess(message, title = "Success") {
    Swal.fire({
      ...this.defaultOptions,
      title: title,
      text: message,
      icon: "success",
      customClass: {
        popup: "swal-toast-success",
      },
    });
  }

  static showInfo(message, title = "Information") {
    Swal.fire({
      ...this.defaultOptions,
      title: title,
      text: message,
      icon: "info",
      customClass: {
        popup: "swal-toast-info",
      },
    });
  }

  static showWarning(message, title = "Warning") {
    Swal.fire({
      ...this.defaultOptions,
      title: title,
      text: message,
      icon: "warning",
      customClass: {
        popup: "swal-toast-warning",
      },
    });
  }
}