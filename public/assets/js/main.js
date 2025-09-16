class LEOPlatform {
    constructor() {
        this.isAdmin = window.LEO && window.LEO.isAdmin === true;

        const modal = document.getElementById('course-modal');
        if (modal) {
            modal.style.display = 'none';
        }

        this.init();
        this.showModalOnFirstVisit();
    }

    async init() {
        this.initSearch();
        this.initUserProfile();
        await this.fetchCourses();
        this.initCourseCards();
        this.initAccessibility();
    }

    showModalOnFirstVisit() {
        if (!localStorage.getItem('hasVisited')) {
            const modal = document.getElementById('welcome-modal');
            if (modal) {
                modal.style.display = 'flex';
                modal.querySelector('.modal-close').addEventListener('click', () => {
                    modal.style.display = 'none';
                    localStorage.setItem('hasVisited', 'false');
                });

                modal.querySelector('.cta-button').addEventListener('click', () => {
                    modal.style.display = 'none';
                    localStorage.setItem('hasVisited', 'false');
                });
            }
        }
    }

    async fetchCourses() {
        try {
            const response = await fetch('/courses');
            if (!response.ok) {
                throw new Error('Erro ao buscar cursos');
            }
            const courses = await response.json();
            this.courses = courses;
            this.renderCourses(courses);
        } catch (error) {
            console.error(error);
            const coursesGrid = document.querySelector('.courses-grid');
            if (coursesGrid) {
                coursesGrid.innerHTML = '<p>Erro ao carregar os cursos.</p>';
            }
        }
    }

    renderCourses(courses) {
        const coursesGrid = document.querySelector('.courses-grid');
        if (!coursesGrid) return;

        coursesGrid.innerHTML = '';

        courses.forEach(course => {
            const article = document.createElement('article');
            article.className = 'course-card';
            article.setAttribute('data-id', course.id);

            if (this.isAdmin) {
                article.classList.add('admin-hover');
            }

            article.innerHTML = `
                <div class="course-image">
                    <img src="${course.image ? '/image/' + course.image : 'https://placehold.co/300x200?text='+course.title}" alt="${course.title}" />
                </div>
                <div class="course-content">
                    <h3>${course.title}</h3>
                    <p>${course.description}</p>
                    <button class="course-btn">VER CURSO</button>
                    ${this.isAdmin ? `
                    <div class="course-actions">
                        <button class="edit-btn" data-id="${course.id}" title="Editar curso"><i class="fas fa-edit"></i> Editar</button>
                        <button class="delete-btn" data-id="${course.id}" title="Excluir curso"><i class="fas fa-trash"></i>Excluir</button>
                    </div>
                    ` : ''}
                </div>
            `;

            coursesGrid.appendChild(article);
        });

        const addCourseCard = document.createElement('div');
        addCourseCard.className = 'add-course-card';
        addCourseCard.innerHTML = `
            <div class="add-course-content">
                <i class="fas fa-plus-circle"></i>
                <h3>ADICIONAR<br>CURSO</h3>
            </div>
        `;
        coursesGrid.appendChild(addCourseCard);

        this.attachCourseCardEvents();
    }

    initCourseCards() {
        this.attachCourseCardEvents();
    }

    attachCourseCardEvents() {
        const courseCards = document.querySelectorAll('.course-card');
        courseCards.forEach(card => {
            card.addEventListener('click', () => {
                const courseId = card.getAttribute('data-id');
                const course = this.courses.find(c => c.id == courseId);
                this.openCourseViewModal(course);
            });
        });

        if (this.isAdmin) {
            const editBtns = document.querySelectorAll('.edit-btn');
            editBtns.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const courseId = btn.getAttribute('data-id');
                    const course = this.courses.find(c => c.id == courseId);
                    this.openCourseModal(course);
                });
            });

            const deleteBtns = document.querySelectorAll('.delete-btn');
            deleteBtns.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const courseId = btn.getAttribute('data-id');
                    this.openDeleteModal(courseId);
                });
            });
        }

        const addCourseCard = document.querySelector('.add-course-card');
        if (addCourseCard) {
            addCourseCard.addEventListener('click', () => {
                this.openCourseModal();
            });
        }
    }

    openCourseModal(course = null) {
        const modal = document.getElementById('course-modal');
        if (!modal) return;

        modal.style.display = 'flex';
        const form = modal.querySelector('#course-form');
        form.reset();
        form.querySelector('.form-error').textContent = '';

        if (course) {
            modal.querySelector('#modal-title').textContent = 'Editar Curso';
            form.courseId.value = course.id;
            form.title.value = course.title;
            form.description.value = course.description;
            form.link.value = course.link || '';
        } else {
            modal.querySelector('#modal-title').textContent = 'Adicionar Curso';
            form.courseId.value = '';
        }

        form.title.focus();

        form.onsubmit = (e) => {
            e.preventDefault();
            this.submitCourseForm();
        };

        modal.querySelector('.modal-close').onclick = () => {
            modal.style.display = 'none';
        };
    }

    openCourseViewModal(course) {
        const modal = document.createElement('div');
        modal.className = 'modal-overlay';
        modal.style.display = 'flex';
        modal.innerHTML = `
            <div class="modal-content">
                <header class="modal-header">
                    <h2>${course.title}</h2>
                    <button type="button" class="modal-close" aria-label="Fechar modal">&times;</button>
                </header>
                <div class="course-view">
                    <img src="${course.image ? '/image/' + course.image : 'https://placehold.co/300x200?text='+course.title}" alt="${course.title}" style="width: 100%; max-width: 300px; height: auto; margin-bottom: 1rem;" />
                    <p><strong>Descrição:</strong> ${course.description}</p>
                    ${course.link ? `<p><strong>Categoria:</strong> ${course.link}</p>` : ''}
                </div>
            </div>
        `;

        document.body.appendChild(modal);

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.remove();
            }
        });

        modal.querySelector('.modal-close').addEventListener('click', (e) => {
            e.stopPropagation();
            modal.remove();
        });
    }

    openDeleteModal(courseId) {
        const modal = document.createElement('div');
        modal.className = 'modal-overlay';
        modal.style.display = 'flex';
        modal.innerHTML = `
            <div class="modal-content">
                <header class="modal-header">
                    <h2>Confirmar Exclusão</h2>
                    <button type="button" class="modal-close" aria-label="Fechar modal">&times;</button>
                </header>
                <p>Tem certeza que deseja excluir este curso? Esta ação não pode ser desfeita.</p>
                <div class="delete-message" style="display: none; color: green; margin-top: 1rem;"></div>
                <div class="modal-actions">
                    <button class="cancel-btn">Cancelar</button>
                    <button class="delete-confirm-btn">Excluir</button>
                </div>
            </div>
        `;

        document.body.appendChild(modal);

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.remove();
            }
        });

        modal.querySelector('.modal-close').addEventListener('click', (e) => {
            e.stopPropagation();
            modal.remove();
        });

        modal.querySelector('.cancel-btn').addEventListener('click', () => {
            modal.remove();
        });

        modal.querySelector('.delete-confirm-btn').addEventListener('click', () => {
            this.deleteCourse(courseId, modal);
        });
    }

    async deleteCourse(courseId, modal) {
        try {
            const response = await fetch(`/courses/${courseId}`, {
                method: 'DELETE',
            });

            if (!response.ok) {
                throw new Error('Erro ao excluir curso');
            }

            modal.querySelector('.delete-message').textContent = 'Curso excluído com sucesso!';
            modal.querySelector('.delete-message').style.display = 'block';
            modal.querySelector('.modal-actions').style.display = 'none';
            setTimeout(() => {
                modal.remove();
                this.fetchCourses();
            }, 2000);
        } catch (error) {
            modal.querySelector('.delete-message').textContent = error.message;
            modal.querySelector('.delete-message').style.color = 'red';
            modal.querySelector('.delete-message').style.display = 'block';
        }
    }

    async submitCourseForm() {
        const modal = document.getElementById('course-modal');
        if (!modal) return;

        const form = modal.querySelector('#course-form');
        const courseId = form.courseId.value;
        const url = courseId ? `/courses/${courseId}` : '/courses';
        const method = 'POST';

        const formData = new FormData(form);

        try {
            const response = await fetch(url, {
                method: method,
                body: formData,
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.error || 'Erro ao salvar curso');
            }

            const message = courseId ? 'Curso editado com sucesso!' : 'Curso adicionado com sucesso!';
            form.querySelector('.form-error').textContent = message;
            form.querySelector('.form-error').style.color = 'green';
            setTimeout(() => {
                modal.style.display = 'none';
                this.fetchCourses();
            }, 2000);
        } catch (error) {
            form.querySelector('.form-error').textContent = error.message;
            form.querySelector('.form-error').style.color = 'red';
        }
    }

    initSearch() {
        const searchInput = document.querySelector('.search-bar input');
        const searchBtn = document.querySelector('.search-bar button');

        if (searchInput && searchBtn) {
            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    this.performSearch(searchInput.value);
                }
            });

            searchBtn.addEventListener('click', () => {
                this.performSearch(searchInput.value);
            });

            let searchTimeout;
            searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    if (e.target.value.length >= 3) {
                        this.performLiveSearch(e.target.value);
                    }
                }, 300);
            });
        }
    }

    performSearch(query) {
        if (!query.trim()) return;

        console.log('Buscando por:', query);
        // Aqui você implementaria a lógica de busca real
        // Por exemplo, filtrar cursos ou redirecionar para página de resultados

        // Simulação de busca
        this.highlightSearchResults(query);
    }

    performLiveSearch(query) {
        console.log('Busca em tempo real:', query);
        // Implementar sugestões de busca em tempo real
    }

    highlightSearchResults(query) {
        const courseCards = document.querySelectorAll('.course-card');
        const searchTerm = query.toLowerCase();

        courseCards.forEach(card => {
            const title = card.querySelector('h3').textContent.toLowerCase();
            const description = card.querySelector('p').textContent.toLowerCase();

            if (title.includes(searchTerm) || description.includes(searchTerm)) {
                card.style.border = '2px solid #4A90E2';
                card.style.transform = 'scale(1.02)';
            } else {
                card.style.border = 'none';
                card.style.transform = 'scale(1)';
            }
        });

        setTimeout(() => {
            courseCards.forEach(card => {
                card.style.border = 'none';
                card.style.transform = 'scale(1)';
            });
        }, 3000);
    }

    initUserProfile() {
        const userProfile = document.querySelector('.user-profile');

        if (userProfile) {
            userProfile.addEventListener('click', () => {
                // Aqui você implementaria o dropdown do usuário
                console.log('Menu do usuário clicado');
                this.showUserMenu();
            });
        }
    }

    showUserMenu() {
        const userMenu = document.createElement('div');
        userMenu.className = 'user-dropdown';
        userMenu.innerHTML = `
            <div class="user-menu-item">Meu Perfil</div>
            <div class="user-menu-item">Configurações</div>
            <div class="user-menu-item"><a href="/logout">Sair</a></div>
        `;

        Object.assign(userMenu.style, {
            position: 'absolute',
            top: '100%',
            right: '0',
            background: 'white',
            border: '1px solid #ddd',
            borderRadius: '8px',
            boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
            zIndex: '1000',
            minWidth: '150px'
        });

        const userProfile = document.querySelector('.user-profile');
        userProfile.style.position = 'relative';
        userProfile.appendChild(userMenu);

        setTimeout(() => {
            document.addEventListener('click', function removeMenu(e) {
                if (!userProfile.contains(e.target)) {
                    if (userMenu.parentNode) {
                        userMenu.remove();
                    }
                    document.removeEventListener('click', removeMenu);
                }
            });
        }, 100);
    }

    initAccessibility() {
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const modals = document.querySelectorAll('.modal-overlay');
                modals.forEach(modal => modal.remove());
            }
        });

        const focusableElements = document.querySelectorAll(
            'a, button, input, textarea, select, details, [tabindex]:not([tabindex="-1"])'
        );

        focusableElements.forEach(element => {
            element.addEventListener('focus', (e) => {
                e.target.style.outline = '2px solid #4A90E2';
                e.target.style.outlineOffset = '2px';
            });

            element.addEventListener('blur', (e) => {
                e.target.style.outline = 'none';
            });
        });
    }


    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new LEOPlatform();
});

const dynamicStyles = `
    .user-menu-item {
        padding: 0.75rem 1rem;
        cursor: pointer;
        transition: background-color 0.2s ease;
        border-bottom: 1px solid #f1f3f4;
    }

    .user-menu-item:hover {
        background-color: #f8f9fa;
    }

    .user-menu-item:last-child {
        border-bottom: none;
        color: #e74c3c;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e9ecef;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #6c757d;
        transition: color 0.2s ease;
    }

    .modal-close:hover {
        color: #e74c3c;
    }

    .sr-only {
        position: absolute !important;
        width: 1px !important;
        height: 1px !important;
        padding: 0 !important;
        margin: -1px !important;
        overflow: hidden !important;
        clip: rect(0, 0, 0, 0) !important;
        white-space: nowrap !important;
        border: 0 !important;
    }

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 1rem;
    }

    .cancel-btn {
        background: #6c757d;
        color: white;
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .cancel-btn:hover {
        background: #5a6268;
    }

    .delete-confirm-btn {
        background: #e74c3c;
        color: white;
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .delete-confirm-btn:hover {
        background: #c0392b;
    }

    .course-actions button {
        background: rgba(0,0,0,0.6);
        border: none;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.875rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .course-actions button:hover {
        background: #e74c3c;
    }

    .course-view {
        text-align: center;
    }

    .course-view img {
        border-radius: 8px;
    }

    .course-view p {
        text-align: left;
        margin-bottom: 0.5rem;
    }
`;

const styleSheet = document.createElement('style');
styleSheet.textContent = dynamicStyles;
document.head.appendChild(styleSheet);
