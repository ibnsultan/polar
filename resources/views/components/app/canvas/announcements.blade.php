<div class="offcanvas pc-announcement-offcanvas offcanvas-end" tabindex="-1" id="announcement" aria-labelledby="announcementLabel">
    <div class="offcanvas-header mb-0 pb-0">
        <h5 class="offcanvas-title" id="announcementLabel">@lang('What\'s new announcement?')</h5>
        <button data-pc-dismiss="#announcement" class="btn-close" >
            <i class="fa fa-x"></i>
        </button>
    </div>
    <div class="offcanvas-body announcement-scroll-block !pt-0">
        <div id="announcements-container">
            <div class="text-center py-4" id="loading">
                <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                <p class="text-muted mt-2">@lang('Loading announcements...')</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadAnnouncements();
});

async function loadAnnouncements() {
    try {
        const response = await fetch(`@route('announcements')`);
        const data = await response.json();
        
        const container = document.getElementById('announcements-container');
        const loading = document.getElementById('loading');
        
        if (data.announcements && data.announcements.length > 0) {
            container.innerHTML = renderAnnouncements(data.announcements);
        } else {
            container.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-bullhorn fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">@lang('No announcements')</h5>
                    <p class="text-muted">@lang('There are no announcements at the moment')</p>
                </div>
            `;
        }
        
        loading.style.display = 'none';
    } catch (error) {
        console.error('Error loading announcements:', error);
        document.getElementById('announcements-container').innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <h5 class="text-muted">@lang('Error loading announcements')</h5>
                <p class="text-muted">@lang('Please try again later')</p>
            </div>
        `;
        document.getElementById('loading').style.display = 'none';
    }
}

function renderAnnouncements(announcements) {
    let html = '';
    let currentDate = '';
    
    announcements.forEach(announcement => {
        const createdAt = new Date(announcement.created_at);
        const today = new Date();
        const yesterday = new Date(today);
        yesterday.setDate(yesterday.getDate() - 1);
        
        let dateLabel = '';
        if (createdAt.toDateString() === today.toDateString()) {
            dateLabel = '@lang('Today')';
        } else if (createdAt.toDateString() === yesterday.toDateString()) {
            dateLabel = '@lang('Yesterday')';
        } else {
            dateLabel = createdAt.toLocaleDateString();
        }
        
        if (dateLabel !== currentDate) {
            html += `<p class="text-span mb-3 mt-4">${dateLabel}</p>`;
            currentDate = dateLabel;
        }
        
        html += `
            <div class="card mb-3">
                <div class="card-body">
                    <div class="items-center flex wrap gap-2 mb-3">
                        <div class="badge text-primary-500 bg-primary-500/10 text-sm">@lang('Announcement')</div>
                        <p class="mb-0 text-muted">${getTimeAgo(createdAt)}</p>
                    </div>
                    <h5 class="mb-3">${escapeHtml(announcement.title)}</h5>
                    <p class="text-muted mb-3">${escapeHtml(announcement.content)}</p>
        `;
        
        if (announcement.image) {
            html += `<img src="/storage/${announcement.image}" alt="Announcement image" class="img-fluid mb-3" />`;
        }
        
        if (announcement.action_url) {
            html += `
                <div class="grid">
                    <a class="btn btn-outline-secondary" href="${announcement.action_url}" target="_blank">@lang('Learn More')</a>
                </div>
            `;
        }
        
        html += `
                </div>
            </div>
        `;
    });
    
    return html;
}

function getTimeAgo(date) {
    const now = new Date();
    const diffInSeconds = Math.floor((now - date) / 1000);
    
    if (diffInSeconds < 60) {
        return `${diffInSeconds} @lang('seconds ago')`;
    } else if (diffInSeconds < 3600) {
        const minutes = Math.floor(diffInSeconds / 60);
        return `${minutes} @lang('minutes ago')`;
    } else if (diffInSeconds < 86400) {
        const hours = Math.floor(diffInSeconds / 3600);
        return `${hours} @lang('hours ago')`;
    } else {
        const days = Math.floor(diffInSeconds / 86400);
        return `${days} @lang('days ago')`;
    }
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}
</script>