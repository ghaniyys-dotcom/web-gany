import React from 'react';
import ReactDOM from 'react-dom/client';
import Lenis from 'lenis';
window.Lenis = Lenis;

import Hero3D from './Hero3D';
import HeroHeadline from './HeroHeadline';
import AboutContent from './AboutContent';
import InteractiveEstimator from './InteractiveEstimator';

// 1. Mount the 3D Hologram Hero component if container exists in Blade
try {
  const container = document.getElementById('hero-3d-hologram');
  if (container) {
    const logosData = container.getAttribute('data-logos');
    let skills = [];
    try {
      skills = logosData ? JSON.parse(logosData) : [];
    } catch (e) {
      console.error('Failed to parse 3D orbit skills from database', e);
    }
    
    const root = ReactDOM.createRoot(container);
    root.render(React.createElement(Hero3D, { initialSkills: skills }));
  }
} catch (error) {
  console.error("Failed to mount Hero3D component:", error);
}

// 2. Mount the Hero Headline with staggered Framer Motion reveal
try {
  const headlineContainer = document.getElementById('hero-headline-mount');
  if (headlineContainer) {
    const title = headlineContainer.getAttribute('data-title') || '';
    const root = ReactDOM.createRoot(headlineContainer);
    root.render(React.createElement(HeroHeadline, { title }));
  }
} catch (error) {
  console.error("Failed to mount HeroHeadline component:", error);
}

// 3. Mount the About Founder Content scroll reveal component
try {
  const aboutContainer = document.getElementById('about-content-mount');
  if (aboutContainer) {
    const eyebrow = aboutContainer.getAttribute('data-eyebrow') || '';
    const heading = aboutContainer.getAttribute('data-heading') || '';
    const description = aboutContainer.getAttribute('data-description') || '';
    const signature = aboutContainer.getAttribute('data-signature') || '';

    const root = ReactDOM.createRoot(aboutContainer);
    root.render(
      React.createElement(AboutContent, {
        eyebrow,
        heading,
        description,
        signature
      })
    );
  }
} catch (error) {
  console.error("Failed to mount AboutContent component:", error);
}

// 4. Mount the Interactive Estimator Budget Calculator
try {
  const estimatorContainer = document.getElementById('interactive-estimator-mount');
  if (estimatorContainer) {
    const pricingAttr = estimatorContainer.getAttribute('data-pricing');
    let pricing = null;
    try {
      pricing = pricingAttr ? JSON.parse(pricingAttr) : null;
    } catch (e) {
      console.error("Failed to parse dynamic pricing data", e);
    }
    const root = ReactDOM.createRoot(estimatorContainer);
    root.render(React.createElement(InteractiveEstimator, { pricing }));
  }
} catch (error) {
  console.error("Failed to mount InteractiveEstimator component:", error);
}

