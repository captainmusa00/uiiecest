<script>
    const courseDetails = {
        'agricultural-education': {
            title: 'Agricultural Education',
            whyEnroll: 'This course provides essential knowledge for anyone looking to thrive in the agricultural sector.',
            aimsObjectives: 'To educate students on sustainable farming practices and modern agricultural technologies.',
            whatYouWillGain: 'You will gain practical skills and knowledge that are in high demand in today\'s agricultural landscape.'
        },
        'automobile-technology': {
            title: 'Automobile Technology',
            whyEnroll: 'Understand the latest trends in automotive technology and enhance your repair skills.',
            aimsObjectives: 'To train students on the principles of automobile engineering and repair methodologies.',
            whatYouWillGain: 'You will acquire the skills to diagnose and repair various automobile issues effectively.'
        },
        'electrical-electronics': {
            title: 'Electrical Electronics Technology',
            whyEnroll: 'Learn about advanced electrical systems and electronics to stay ahead in this evolving field.',
            aimsObjectives: 'To prepare students with hands-on training in electrical and electronic systems.',
            whatYouWillGain: 'You will become proficient in designing and maintaining electrical circuits and systems.'
        },
        'web-development-ai': {
            title: 'Web Development Using AI',
            whyEnroll: 'Harness the power of AI in web development to create smart, user-friendly applications.',
            aimsObjectives: 'To equip students with the skills needed to integrate AI technologies into web solutions.',
            whatYouWillGain: 'You will learn how to build modern web applications that utilize AI for enhanced functionality.'
        }
    };

    function openModal(courseId) {
        const course = courseDetails[courseId];
        document.getElementById('modal-course-title').innerText = course.title;
        document.getElementById('modal-why-enroll').innerText = course.whyEnroll;
        document.getElementById('modal-aims-objectives').innerText = course.aimsObjectives;
        document.getElementById('modal-what-you-will-gain').innerText = course.whatYouWillGain;
        document.getElementById('course-modal').style.display = "block"; // Show modal
    }

    function closeModal() {
        document.getElementById('course-modal').style.display = "none"; // Hide modal
    }

    // Close modal when clicking outside of the modal content
    window.onclick = function(event) {
        if (event.target == document.getElementById('course-modal')) {
            closeModal();
        }
    }
</script>
