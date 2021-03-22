function alertError(error) {
  if(!error.hasOwnProperty('responseJSON')) {
    toastr.error(error, 'Oops!', {
      positionClass: 'toast-top-center',
      containerId: 'toast-top-center'
    });
  }
  else if (error.status === 422) {
    for (let key in error.responseJSON.errors) {
      toastr.error(error.responseJSON.errors[key], 'Oops!', {
        positionClass: 'toast-top-center',
        containerId: 'toast-top-center'
      });
    }
  } else {
    toastr.error(error.responseJSON.message, 'Oops!', {
      positionClass: 'toast-top-center',
      containerId: 'toast-top-center'
    });
  }
}
