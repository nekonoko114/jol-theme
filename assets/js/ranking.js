class m{constructor(){this.config={ajaxUrl:"/wp-admin/admin-ajax.php",refreshInterval:3e4,animationDuration:500,loadMoreLimit:10},this.state={currentPeriod:"total",currentCategory:"",currentSort:"desc",currentPage:1,isLoading:!1,autoRefresh:!0},this.elements={},this.refreshTimer=null}init(){this.cacheElements(),this.bindEvents(),this.initializeRanking(),this.startAutoRefresh(),this.initializeParticles(),console.log("ランキングマネージャー初期化完了")}cacheElements(){this.elements={periodFilter:document.getElementById("period-filter"),categoryFilter:document.getElementById("category-filter"),sortFilter:document.getElementById("sort-filter"),applyBtn:document.getElementById("apply-filters"),resetBtn:document.getElementById("reset-filters"),topRanking:document.getElementById("top-ranking"),generalRanking:document.getElementById("general-ranking"),loadMoreBtn:document.getElementById("load-more-ranking"),lastUpdateTime:document.getElementById("last-update-time"),rankingPage:document.querySelector(".ranking-page")}}bindEvents(){this.elements.applyBtn&&this.elements.applyBtn.addEventListener("click",()=>this.applyFilters()),this.elements.resetBtn&&this.elements.resetBtn.addEventListener("click",()=>this.resetFilters()),this.elements.loadMoreBtn&&this.elements.loadMoreBtn.addEventListener("click",()=>this.loadMoreRanking()),[this.elements.periodFilter,this.elements.categoryFilter,this.elements.sortFilter].forEach(e=>{e&&e.addEventListener("change",()=>this.applyFilters())}),this.bindCardEvents(),window.addEventListener("beforeunload",()=>this.cleanup())}bindCardEvents(){document.addEventListener("mouseover",e=>{const t=e.target.closest(".ranking-card");t&&this.animateCard(t,"enter")}),document.addEventListener("mouseout",e=>{const t=e.target.closest(".ranking-card");t&&!t.contains(e.relatedTarget)&&this.animateCard(t,"leave")})}async initializeRanking(){this.showLoading(!0);try{await this.loadRankingData(),this.animateRankingCards()}catch(e){console.error("初期ランキング読み込みエラー:",e),this.showError("ランキングデータの読み込みに失敗しました")}finally{this.showLoading(!1)}}async loadRankingData(e=!1){const t=new FormData;t.append("action","get_ranking_data"),t.append("period",this.state.currentPeriod),t.append("category",this.state.currentCategory),t.append("sort",this.state.currentSort),t.append("limit",this.config.loadMoreLimit),t.append("offset",e?(this.state.currentPage-1)*this.config.loadMoreLimit:0);try{const n=await(await fetch(this.config.ajaxUrl,{method:"POST",body:t})).json();if(n.success)this.updateRankingDisplay(n.data,e),this.updateLastUpdateTime();else throw new Error(n.data||"データ取得に失敗しました")}catch(a){throw console.error("Ajax通信エラー:",a),a}}updateRankingDisplay(e,t=!1){if(!e||e.length===0){t||this.showNoData();return}t?this.updateGeneralRanking(e,!0):(this.updateTopRanking(e.slice(0,3)),this.updateGeneralRanking(e.slice(3),!1)),this.updateLoadMoreButton(e.length>=this.config.loadMoreLimit)}updateTopRanking(e){this.elements.topRanking&&(this.elements.topRanking.innerHTML="",e.forEach((t,a)=>{const n=this.createPodiumRank(t,a+1);this.elements.topRanking.appendChild(n)}),this.animatePodium())}updateGeneralRanking(e,t=!1){this.elements.generalRanking&&(t||(this.elements.generalRanking.innerHTML=""),e.forEach(a=>{const n=this.createRankingCard(a);this.elements.generalRanking.appendChild(n)}))}escapeHtml(e){return e==null?"":String(e).replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;").replace(/'/g,"&#39;")}sanitizeUrl(e){if(!e||typeof e!="string")return"#";const t=e.trim();if(t.startsWith("/"))return this.escapeHtml(t);try{const a=new URL(t,window.location.origin);if(a.protocol==="http:"||a.protocol==="https:")return this.escapeHtml(a.href)}catch{}return"#"}createPodiumRank(e,t){const a=Number(t)||0,n=this.escapeHtml(e.name),o=this.sanitizeUrl(e.avatar),r=this.sanitizeUrl(e.url),s=(Number(e.gift_count)||0).toLocaleString(),i=document.createElement("div");i.className=`podium-rank rank-${a}`,i.dataset.rank=a;const l=this.getTrendIcon(e.trend),c={1:"/wp-content/themes/jol-themes/assets/images/ranking/clown-gold.webp",2:"/wp-content/themes/jol-themes/assets/images/ranking/clown-silver.webp",3:"/wp-content/themes/jol-themes/assets/images/ranking/clown-brons.webp"},h=c[a]?`<div class="rank-badge"><img src="${c[a]}" alt="rank-${a}" class="rank-icon"></div>`:`<div class="rank-badge">${a}</div>`;return i.innerHTML=`
      ${h}
      <div class="liver-avatar">
        <img src="${o}" alt="${n}">
      </div>
      <div class="liver-info">
        <h3 class="liver-name">${n}</h3>
        <div class="gift-count">${s} ギフト</div>
        <div class="gift-trend">${l}</div>
      </div>
    `,i.addEventListener("click",()=>{r&&r!=="#"&&window.open(r,"_blank","noopener,noreferrer")}),i}createRankingCard(e){const t=Number(e.rank)||0,a=this.escapeHtml(e.name),n=this.sanitizeUrl(e.avatar),o=this.sanitizeUrl(e.url),r=(Number(e.gift_count)||0).toLocaleString(),s=document.createElement("div");s.className="ranking-card",s.dataset.rank=t,t<=3?s.classList.add(`rank-top-${t}`):t<=10&&s.classList.add("rank-top-ten");const i=this.getTrendIcon(e.trend),l={1:"/wp-content/themes/jol-themes/assets/images/ranking/clown-gold.webp",2:"/wp-content/themes/jol-themes/assets/images/ranking/clown-silver.webp",3:"/wp-content/themes/jol-themes/assets/images/ranking/clown-brons.webp"},c=l[t]?`<div class="rank-badge"><img src="${l[t]}" alt="rank-${t}" class="rank-icon"></div>`:`<div class="rank-badge"><span class="rank-number">${t}</span></div>`;return s.innerHTML=`
            <div class="ranking-card-inner">
                ${c}
                
                <div class="liver-info">
                    <div class="liver-avatar">
                        <img src="${n}" alt="${a}">
                        ${t<=3?'<div class="crown-overlay"></div>':""}
                    </div>
                    
                    <div class="liver-details">
                        <h3 class="liver-name">
                            <a href="${o}" target="_blank" rel="noopener noreferrer">${a}</a>
                        </h3>
                        
                        <div class="gift-info">
                            <div class="gift-count">
                                <i class="fas fa-gift"></i>
                                <span class="count-number">${r}</span>
                                <span class="count-label">ギフト</span>
                            </div>
                            
                            <div class="gift-trend">
                                <span class="trend-icon">${i}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-actions">
                    <a href="${o}" class="btn-view-profile" target="_blank" rel="noopener noreferrer">
                        <i class="fas fa-user"></i>
                        プロフィール
                    </a>
          <!-- 応援ボタンは廃止 -->
                </div>
            </div>
            
            <div class="card-glow"></div>
        `,s}getTrendIcon(e){return{up:"📈",down:"📉",stable:"📊"}[e]||"📊"}async applyFilters(){var e,t,a;if(!this.state.isLoading){this.state.currentPeriod=((e=this.elements.periodFilter)==null?void 0:e.value)||"total",this.state.currentCategory=((t=this.elements.categoryFilter)==null?void 0:t.value)||"",this.state.currentSort=((a=this.elements.sortFilter)==null?void 0:a.value)||"desc",this.state.currentPage=1,this.showLoading(!0);try{await this.loadRankingData(),this.animateRankingCards()}catch{this.showError("フィルター適用に失敗しました")}finally{this.showLoading(!1)}}}resetFilters(){this.elements.periodFilter&&(this.elements.periodFilter.value="total"),this.elements.categoryFilter&&(this.elements.categoryFilter.value=""),this.elements.sortFilter&&(this.elements.sortFilter.value="desc"),this.applyFilters()}async loadMoreRanking(){if(!this.state.isLoading){this.state.currentPage++,this.showLoading(!0,"もっと読み込み中...");try{await this.loadRankingData(!0)}catch{this.state.currentPage--,this.showError("追加データの読み込みに失敗しました")}finally{this.showLoading(!1)}}}sendGift(e){}animateCard(e,t){t==="enter"?(e.style.transform="translateY(-5px) scale(1.02)",e.style.boxShadow="0 15px 35px rgba(0, 0, 0, 0.2)"):(e.style.transform="",e.style.boxShadow="")}animatePodium(){var t;const e=(t=this.elements.topRanking)==null?void 0:t.querySelectorAll(".podium-rank");e&&e.forEach((a,n)=>{setTimeout(()=>{a.style.opacity="0",a.style.transform="translateY(50px) scale(0.8)",requestAnimationFrame(()=>{a.style.transition="all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1)",a.style.opacity="1",a.style.transform=""})},n*200)})}animateRankingCards(){var t;const e=(t=this.elements.generalRanking)==null?void 0:t.querySelectorAll(".ranking-card");e&&e.forEach((a,n)=>{setTimeout(()=>{a.style.opacity="0",a.style.transform="translateX(-30px)",requestAnimationFrame(()=>{a.style.transition="all 0.4s ease",a.style.opacity="1",a.style.transform=""})},n*100)})}animateGiftSend(e){}initializeParticles(){document.getElementById("ranking-particles")&&typeof particlesJS<"u"&&particlesJS("ranking-particles",{particles:{number:{value:50},color:{value:["#ffffff","#ffd700"]},shape:{type:"star"},opacity:{value:.6,anim:{enable:!0}},size:{value:2,anim:{enable:!0}},move:{enable:!0,speed:1}}})}startAutoRefresh(){this.state.autoRefresh&&(this.refreshTimer=setInterval(()=>{!this.state.isLoading&&document.visibilityState==="visible"&&this.loadRankingData()},this.config.refreshInterval))}stopAutoRefresh(){this.refreshTimer&&(clearInterval(this.refreshTimer),this.refreshTimer=null)}showLoading(e,t="ランキングを更新中..."){if(this.state.isLoading=e,e){const a=document.createElement("div");a.id="ranking-loader",a.innerHTML=`
                <div class="loader-content">
                    <div class="loader-spinner"></div>
                    <p>${this.escapeHtml(t)}</p>
                </div>
            `,a.style.cssText=`
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 10000;
            `,document.body.appendChild(a)}else{const a=document.getElementById("ranking-loader");a&&a.remove()}}showError(e){this.showNotification(e,"error")}showNoData(){this.elements.generalRanking&&(this.elements.generalRanking.innerHTML=`
                <div class="no-data-message">
                    <i class="fas fa-search"></i>
                    <h3>ランキングデータがありません</h3>
                    <p>条件を変更して再度お試しください</p>
                </div>
            `)}showNotification(e,t="info"){const a=["success","error","info"].includes(t)?t:"info",n=document.createElement("div");n.className=`ranking-notification ${a}`,n.innerHTML=`
            <div class="notification-content">
                <i class="fas fa-${a==="success"?"check-circle":a==="error"?"exclamation-circle":"info-circle"}"></i>
                <span>${this.escapeHtml(e)}</span>
            </div>
        `,n.style.cssText=`
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${t==="success"?"#4caf50":t==="error"?"#f44336":"#2196f3"};
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            z-index: 10001;
            animation: slideInRight 0.3s ease;
        `,document.body.appendChild(n),setTimeout(()=>{n.style.animation="slideOutRight 0.3s ease forwards",setTimeout(()=>n.remove(),300)},3e3)}updateLastUpdateTime(){if(this.elements.lastUpdateTime){const t=new Date().toLocaleTimeString("ja-JP",{hour:"2-digit",minute:"2-digit"});this.elements.lastUpdateTime.textContent=`最終更新: ${t}`}}updateLoadMoreButton(e){this.elements.loadMoreBtn&&(this.elements.loadMoreBtn.disabled=!e,this.elements.loadMoreBtn.textContent=e?"もっと見る":"すべて表示済み")}cleanup(){this.stopAutoRefresh(),console.log("ランキングマネージャー クリーンアップ完了")}}const d=document.createElement("style");d.textContent=`
    @keyframes heartFloat {
        0% { transform: translateY(0) scale(1); opacity: 1; }
        100% { transform: translateY(-100px) scale(0.5); opacity: 0; }
    }
    
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
    
    .loader-spinner {
        width: 40px;
        height: 40px;
        border: 4px solid rgba(255, 255, 255, 0.3);
        border-top: 4px solid #ffffff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 15px;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .loader-content {
        text-align: center;
        color: white;
        font-weight: 600;
    }
`;document.head.appendChild(d);window.RankingManager=m;
