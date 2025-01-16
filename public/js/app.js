const themeToggle = document.getElementById('themeToggle');
const body = document.body;
const logoImage = document.getElementById('szabist-logo');

// Set default theme to 'light'
const currentTheme = localStorage.getItem('theme') || 'light';

function updateTheme(theme) {
    if (theme === 'dark') {
        body.classList.add('dark-theme');
        logoImage.src = 'images/SZABIST_logo.jpeg';
    } else {
        body.classList.remove('dark-theme');
        logoImage.src = 'images/zablogo.jpeg';
    }
    localStorage.setItem('theme', theme);
}

themeToggle.addEventListener('click', () => {
    const newTheme = body.classList.contains('dark-theme') ? 'light' : 'dark';
    updateTheme(newTheme);
});

function openModal() {
    document.getElementById('loginModal').style.display = "block";
}

function closeModal() {
    document.getElementById('loginModal').style.display = "none";
}

function openApplicationModal(jobTitle) {
    document.getElementById('jobTitle').textContent = jobTitle;
    document.getElementById('applicationModal').style.display = 'block';
}

function closeApplicationModal() {
    document.getElementById('applicationModal').style.display = 'none';
}

// Close modal on window click
window.onclick = function (event) {
    const loginModal = document.getElementById('loginModal');
    const applicationModal = document.getElementById('applicationModal');
    
    if (event.target == loginModal) closeModal();
    if (event.target == applicationModal) closeApplicationModal();
}

// Handle form submissions dynamically
// document.getElementById('employerForm').addEventListener('submit', async function (e) {
//     e.preventDefault();
    
//     const employerType = document.getElementById('employerType').value;
//     const email = document.getElementById('employerUsername').value;
//     const password = document.getElementById('employerPassword').value;

//     // Get the CSRF token from the meta tag
//     const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

//     try {
//         const response = await fetch('/', {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json',
//                 'X-CSRF-TOKEN': csrfToken
//             },
//             body: JSON.stringify({
//                 role: employerType,
//                 email: email,
//                 password: password,
//             }),
//         });

//         if (response.ok) {
//             const data = await response.json();
//             alert(`${employerType} login successful. Redirecting to dashboard.`);
//             // Redirect based on the response from the server
//             window.location.href = data.redirect || '/dashboard';
//         } else {
//             const errorData = await response.json();
//             alert(errorData.message || 'Invalid credentials or user type.');
//         }
//     } catch (error) {
//         console.error('Error:', error);
//         alert('An error occurred. Please try again.');
//     }
// });

function openModal() {
    document.getElementById('loginModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('loginModal').style.display = 'none';
}

// Close the modal when clicking outside of it
window.onclick = function(event) {
    if (event.target == document.getElementById('loginModal')) {
        closeModal();
    }
}


// // Handle job application submission
// document.getElementById('applicationForm').addEventListener('submit', function (e) {
//     e.preventDefault();
//     const name = document.getElementById('applicantName').value;
//     const email = document.getElementById('applicantEmail').value;
//     const contact = document.getElementById('applicantContact').value;
//     const cv = document.getElementById('applicantCV').files[0].name;

//     alert(`Application submitted:\nName: ${name}\nEmail: ${email}\nContact: ${contact}\nCV: ${cv}`);
//     closeApplicationModal();
//     this.reset();
// });

// // Show job listings dynamically
// function showJobOpenings() {
//     document.getElementById('jobOpenings').style.display = 'block';
//     document.querySelector('.hero').style.display = 'none';
//     displayJobs();
// }

// function showHomePage() {
//     document.getElementById('jobOpenings').style.display = 'none';
//     document.querySelector('.hero').style.display = 'flex';
//     window.scrollTo(0, 0);
// }

// async function displayJobs() {
//     const jobListingsContainer = document.getElementById('jobListings');
//     jobListingsContainer.innerHTML = '';

//     try {
//         const response = await fetch('/api/jobs');
//         const jobs = await response.json();
        
//         if (jobs.length === 0) {
//             jobListingsContainer.innerHTML = '<p>No jobs available at the moment.</p>';
//         } else {
//             jobs.forEach(job => {
//                 const jobElement = document.createElement('div');
//                 jobElement.className = 'job-listing';
//                 jobElement.innerHTML = `
//                     <h3>${job.title}</h3>
//                     <p><strong>Department:</strong> ${job.department}</p>
//                     <p><strong>Description:</strong> ${job.description}</p>
//                     <button onclick="openApplicationModal('${job.title}')" class="btn">Apply</button>
//                 `;
//                 jobListingsContainer.appendChild(jobElement);
//             });
//         }
//     } catch (error) {
//         jobListingsContainer.innerHTML = '<p>Error loading job listings.</p>';
//     }
// }

// // Initial load
// window.onload = async function () {
//     updateTheme(currentTheme);

//     if (!localStorage.getItem('publicJobs')) {
//         const response = await fetch('/api/jobs');
//         const jobs = await response.json();
//         localStorage.setItem('publicJobs', JSON.stringify(jobs));
//     }

//     displayJobs();
// };

// // Chatbot functionality
// function toggleChat() {
//     const chatWindow = document.getElementById('chatWindow');
//     chatWindow.style.display = chatWindow.style.display === 'none' ? 'flex' : 'none';
// }

// async function sendMessage() {
//     const userInput = document.getElementById('userInput');
//     const chatMessages = document.getElementById('chatMessages');

//     if (userInput.value.trim() === '') return;

//     // Add user message
//     const userMessage = document.createElement('div');
//     userMessage.className = 'chat-message user-message';
//     userMessage.textContent = userInput.value;
//     chatMessages.appendChild(userMessage);

//     // Show loading indicator
//     const loadingMessage = document.createElement('div');
//     loadingMessage.className = 'chat-message bot-message';
//     loadingMessage.textContent = "Thinking...";
//     chatMessages.appendChild(loadingMessage);

//     try {
//         const response = await fetch('/api/chatbot/chat', {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json',
//             },
//             body: JSON.stringify({ message: userInput.value }),
//         });

//         const data = await response.json();

//         chatMessages.removeChild(loadingMessage);

//         const botMessage = document.createElement('div');
//         botMessage.className = 'chat-message bot-message';
//         botMessage.textContent = data.reply;
//         chatMessages.appendChild(botMessage);
//     } catch (error) {
//         chatMessages.removeChild(loadingMessage);

//         const errorMessage = document.createElement('div');
//         errorMessage.className = 'chat-message bot-message';
//         errorMessage.textContent = "Sorry, I encountered an error. Please try again later.";
//         chatMessages.appendChild(errorMessage);
//     }

//     userInput.value = '';
//     chatMessages.scrollTop = chatMessages.scrollHeight;
// }

// // Send message on pressing Enter
// document.getElementById('userInput').addEventListener('keypress', function (e) {
//     if (e.key === 'Enter') {
//         sendMessage();
//     }
// });
