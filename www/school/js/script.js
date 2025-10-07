// 부드러운 스크롤 이동
document.addEventListener('DOMContentLoaded', function () {
  const tocLinks = document.querySelectorAll('.toc a');

  tocLinks.forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      // href 속성 값에서 '#' 제거하여 ID만 가져옴
      const targetId = this.getAttribute('href').substring(1);
      // 해당 ID를 가진 요소를 찾음 (h2 또는 section 등 id를 가진 모든 요소 가능)
      const target = document.getElementById(targetId);
      if (target) {
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });

        // 클릭 시 즉시 active 클래스 업데이트 (선택 사항)
        updateActiveLink(targetId);
      }
    });
  });

  // 스크롤 시 현재 위치 하이라이트
  // 스크롤 이벤트 최적화를 위한 디바운스 또는 스로틀 사용 고려 가능 (현재는 기본 구현)
  window.addEventListener('scroll', function() {
    // ID가 있는 모든 section 요소를 선택
    const sections = document.querySelectorAll('section[id]');
    let currentSectionId = "";

    sections.forEach(section => {
      const sectionTop = section.offsetTop - 100; // 섹션 상단 감지 위치 조정 (필요시 값 변경)
      const sectionHeight = section.offsetHeight;
      const scrollPosition = window.pageYOffset || document.documentElement.scrollTop;

      // 현재 스크롤 위치가 섹션 범위 내에 있는지 확인
      if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
        currentSectionId = section.getAttribute('id');
      }
    });

     // 만약 스크롤이 맨 위에 도달했거나, 특정 섹션 범위에 걸리지 않았다면 첫 번째 섹션을 활성화할 수도 있음
     // (또는 아무것도 활성화하지 않도록 currentSectionId가 비어있을 때 처리)
     // 예: 스크롤이 맨 위 근처일 때 첫 번째 섹션 활성화
     if ((window.pageYOffset || document.documentElement.scrollTop) < 150 && sections.length > 0) {
         currentSectionId = sections[0].getAttribute('id');
     }
     // 예: 스크롤이 맨 아래 근처일 때 마지막 섹션 활성화
     else if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 50 && sections.length > 0) {
        currentSectionId = sections[sections.length - 1].getAttribute('id');
     }


    updateActiveLink(currentSectionId);
  });

  // Active 클래스 업데이트 함수
  function updateActiveLink(currentId) {
    const links = document.querySelectorAll('.toc a');
    links.forEach(link => {
      link.classList.remove('active');
      // link의 href 속성 값에서 '#' 제거하고 비교
      if (link.getAttribute('href').substring(1) === currentId) {
        link.classList.add('active');
      }
    });
  }

   // 페이지 로드 시 초기 활성 링크 설정 (선택 사항)
   // 예: URL 해시 값으로 초기 섹션 설정 또는 첫번째 섹션 설정
   let initialSectionId = window.location.hash.substring(1);
   if (!initialSectionId && document.querySelectorAll('section[id]').length > 0) {
       initialSectionId = document.querySelectorAll('section[id]')[0].getAttribute('id');
   }
   if (initialSectionId) {
       updateActiveLink(initialSectionId);
       // 초기 위치로 부드럽게 스크롤 (선택적)
       // const initialTarget = document.getElementById(initialSectionId);
       // if (initialTarget) {
       //    initialTarget.scrollIntoView({ behavior: 'smooth', block: 'start' });
       // }
   }
});