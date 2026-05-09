import Swal from 'sweetalert2'

export const confirmDialog = (title, text = '', confirmText = 'Ya', cancelText = 'Batal') => {
  return Swal.fire({
    title: title,
    text: text,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: confirmText,
    cancelButtonText: cancelText,
    customClass: {
      popup: 'bg-white rounded-2xl shadow-xl border border-gray-100',
      title: 'text-xl font-bold text-gray-800',
      htmlContainer: 'text-gray-600',
      confirmButton: 'bg-red-600 text-white px-5 py-2.5 rounded-lg hover:bg-red-700 transition-colors font-medium text-sm',
      cancelButton: 'bg-gray-100 text-gray-800 px-5 py-2.5 rounded-lg hover:bg-gray-200 ml-3 transition-colors font-medium text-sm border border-gray-200'
    },
    buttonsStyling: false
  })
}

export const successDialog = (title, text = '') => {
  return Swal.fire({
    title: title,
    text: text,
    icon: 'success',
    confirmButtonText: 'OK',
    customClass: {
      popup: 'bg-white rounded-2xl shadow-xl border border-gray-100',
      title: 'text-xl font-bold text-gray-800',
      htmlContainer: 'text-gray-600',
      confirmButton: 'bg-indigo-600 text-white px-5 py-2.5 rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm'
    },
    buttonsStyling: false
  })
}
