window.onload = function () {
    if (performance.navigation.type === 2) {
    
        fetch('/logout', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
        }).then(() => {
            window.location.href = '/login';
        });
    }
};