/* global TP_TEAM_ORDER, TP_TEAM_ORDER_MAP */
(function(){
  function qs(el, sel){ return (el||document).querySelector(sel); }
  function qsa(el, sel){ return Array.prototype.slice.call((el||document).querySelectorAll(sel)); }
  function cssEscape(val){
    if (window.CSS && typeof CSS.escape === 'function') return CSS.escape(val);
    return String(val).replace(/([^a-zA-Z0-9_\-])/g,'\\$1');
  }

  function setActive(buttons, btn){
    buttons.forEach(b => b.classList.toggle('is-active', b===btn));
  }

  function filterGrid(wrapper){
    const grid = qs(wrapper, '.tp-grid');
    const activeRole = qs(wrapper, '.tp-filters .is-active');
    const activeTab  = qs(wrapper, '.tp-tabs .is-active');
    const role = activeRole ? activeRole.getAttribute('data-term') : '__all__';
    const tab  = activeTab  ? activeTab.getAttribute('data-term')  : '__all__';
    qsa(grid, '.tp-card').forEach(card => {
      const roles = (card.getAttribute('data-roles')||'').split(/\s+/).filter(Boolean);
      const tabs  = (card.getAttribute('data-tabs')||'').split(/\s+/).filter(Boolean);
      const roleMatch = (role==='__all__') || roles.includes(role);
      const tabMatch  = (tab==='__all__')  || tabs.includes(tab);
      const show = roleMatch && tabMatch;
      card.classList.toggle('is-hidden', !show);
    });
  }

  function attachFilters(){
    qsa(document, '.tp-controls').forEach(ctrl =>{
      const wrapper = document.getElementById(ctrl.getAttribute('data-grid'))?.closest('.tp-grid-wrapper') || ctrl.closest('.tp-grid-wrapper');
      // Role filters
      qsa(ctrl, '.tp-filter').forEach(btn => {
        btn.addEventListener('click', ()=>{
          setActive(qsa(ctrl, '.tp-filter'), btn);
          filterGrid(wrapper);
        });
      });
      // Tabs
      qsa(ctrl, '.tp-tab').forEach(btn => {
        btn.addEventListener('click', ()=>{
          setActive(qsa(ctrl, '.tp-tab'), btn);
          filterGrid(wrapper);
        });
      });
    });
  }

  // Modal logic
  let openModal = null;
  let currentGridId = null;
  let currentPostId = null;

  function bodyLock(lock){
    document.documentElement.classList.toggle('tp-modal-open', !!lock);
    document.body.style.overflow = lock ? 'hidden' : '';
  }

  function computeOrderFromDOM(gridId){
    return qsa(document, '#'+cssEscape(gridId)+' .tp-card').map(el => parseInt(el.getAttribute('data-post-id'), 10));
  }
  function getOrder(gridId){
    if (window.TP_TEAM_ORDER_MAP && window.TP_TEAM_ORDER_MAP[gridId]) return window.TP_TEAM_ORDER_MAP[gridId].slice();
    if (Array.isArray(window.TP_TEAM_ORDER)) return window.TP_TEAM_ORDER.slice();
    if (gridId) return computeOrderFromDOM(gridId);
    return [];
  }

  function isVisibleInGrid(gridId, postId){
    const card = document.querySelector('#'+cssEscape(gridId)+' .tp-card[data-post-id="'+postId+'"]');
    return card && !card.classList.contains('is-hidden');
  }

  function showModal(postId){
    const modal = document.querySelector('.tp-modals-root .tp-modal[data-tp-modal-id="'+postId+'"]');
    if (!modal) return;
    if (openModal) hideModal(openModal);
    modal.setAttribute('aria-hidden','false');
    openModal = modal;
    currentPostId = parseInt(postId,10);
    // Fallback: infer grid id from modal if missing
    if (!currentGridId) {
      const gid = modal.getAttribute('data-grid-id');
      if (gid) currentGridId = gid;
    }
    bodyLock(true);
  }

  function hideModal(modal){
    (modal||openModal)?.setAttribute('aria-hidden','true');
    openModal = null;
    bodyLock(false);
  }

  function nextPrev(dir){
    if (!currentGridId && openModal){
      currentGridId = openModal.getAttribute('data-grid-id') || currentGridId;
    }
    if (!currentGridId) return;
    const order = getOrder(currentGridId);
    if (!order.length) return;
    let idx = order.indexOf(currentPostId);
    if (idx === -1){
      // Try to recompute order from DOM as a fallback
      const fallback = computeOrderFromDOM(currentGridId);
      idx = fallback.indexOf(currentPostId);
      if (idx !== -1) order.splice(0, order.length, ...fallback);
    }
    for (let i=1;i<=order.length;i++){
      const j = (dir>0) ? (idx+i)%order.length : (idx - i + order.length)%order.length;
      const candidate = order[j];
      if (isVisibleInGrid(currentGridId, candidate)){
        showModal(candidate);
        break;
      }
    }
  }

  function attachCards(){
    qsa(document, '.tp-grid-wrapper').forEach(wrapper => {
      const gridId = wrapper.id || wrapper.querySelector('[id]')?.id;
      qsa(wrapper, '.tp-card-open').forEach(btn => {
        btn.addEventListener('click', () => {
          currentGridId = gridId;
          const id = btn.getAttribute('data-modal-target');
          showModal(parseInt(id,10));
        });
      });
    });

    // Close handlers and nav
    document.addEventListener('click', (e)=>{
      const t = e.target;
      if (t && t.hasAttribute('data-tp-close')){
        hideModal();
      } else if (t && t.hasAttribute('data-tp-prev')){
        nextPrev(-1);
      } else if (t && t.hasAttribute('data-tp-next')){
        nextPrev(1);
      }
    });

    document.addEventListener('keydown', (e)=>{
      if (!openModal) return;
      if (e.key === 'Escape') hideModal();
      if (e.key === 'ArrowLeft') nextPrev(-1);
      if (e.key === 'ArrowRight') nextPrev(1);
    });
  }

  document.addEventListener('DOMContentLoaded', function(){
    attachFilters();
    attachCards();
  });
})();
