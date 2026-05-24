import React, { useEffect, useRef } from 'react';
import { motion } from 'framer-motion';

export default function HeroHeadline({ title }) {
  const containerRef = useRef(null);
  const laserRef = useRef(null);

  // Parse asterisks to allow highlighting arbitrary words in the admin panel
  const parsedWords = [];
  if (title) {
    title.split(' ').forEach((w) => {
      let isHighlighted = false;
      let cleanText = w;
      
      const match = w.match(/\*([^*]+)\*/);
      if (match) {
        isHighlighted = true;
        cleanText = w.replace(/\*[^*]+\*/, match[1]);
      }
      
      parsedWords.push({ text: cleanText, isHighlighted });
    });
  }

  useEffect(() => {
    const container = containerRef.current;
    const laser = laserRef.current;
    if (!container || !laser) return;

    const chars = container.querySelectorAll('.kinetic-char');
    const wordElements = container.querySelectorAll('.kinetic-word');

    // Laser beam positioning on word hover
    wordElements.forEach(word => {
      word.addEventListener('mouseenter', () => {
        const wordLeft = word.offsetLeft;
        const wordWidth = word.offsetWidth;
        laser.style.left = `${wordLeft}px`;
        laser.style.width = `${wordWidth}px`;
      });
    });

    container.addEventListener('mouseleave', () => {
      laser.style.width = '0px';
    });

    // Real-time smooth elastic skew on character mouse proximity
    const handleMouseMove = (e) => {
      const mouseX = e.clientX;
      const mouseY = e.clientY;

      chars.forEach(char => {
        const charRect = char.getBoundingClientRect();
        const charCenterX = charRect.left + charRect.width / 2;
        const charCenterY = charRect.top + charRect.height / 2;

        const deltaX = mouseX - charCenterX;
        const deltaY = mouseY - charCenterY;
        const distance = Math.hypot(deltaX, deltaY);

        const radius = 120;

        if (distance < radius) {
          const influence = (radius - distance) / radius;
          const skewX = (deltaX / radius) * -22 * influence;
          const skewY = (deltaY / radius) * 12 * influence;
          const translateY = -10 * influence;
          const scale = 1 + (0.15 * influence);

          char.style.transition = 'none';
          char.style.transform = `translate3d(0, ${translateY}px, 0) skewX(${skewX}deg) skewY(${skewY}deg) scale(${scale})`;
        } else {
          char.style.transition = '';
          char.style.transform = '';
        }
      });
    };

    const handleMouseLeave = () => {
      chars.forEach(char => {
        char.style.transition = '';
        char.style.transform = '';
      });
    };

    container.addEventListener('mousemove', handleMouseMove);
    container.addEventListener('mouseleave', handleMouseLeave);

    return () => {
      container.removeEventListener('mousemove', handleMouseMove);
      container.removeEventListener('mouseleave', handleMouseLeave);
    };
  }, [title]);

  // Framer Motion variants for staggered reveal
  const containerVariants = {
    hidden: {},
    visible: {
      transition: {
        staggerChildren: 0.03,
        delayChildren: 0.1,
      },
    },
  };

  const charVariants = {
    hidden: {
      y: '100%',
      opacity: 0,
    },
    visible: {
      y: 0,
      opacity: 1,
      transition: {
        duration: 0.85,
        ease: [0.32, 0.72, 0, 1], // The Emil Kowalski custom cubic-bezier
      },
    },
  };

  return (
    <motion.h1
      ref={containerRef}
      className="kinetic-title select-none"
      variants={containerVariants}
      initial="hidden"
      animate="visible"
      style={{
        fontSize: 'clamp(34px, 5vw, 64px)',
        lineHeight: 1.1,
        letterSpacing: '-0.03em', // Tight letter-spacing for premium feel
      }}
    >
      {parsedWords.map((wordObj, wordIndex) => {
        return (
          <span
            key={wordIndex}
            className={`kinetic-word ${wordObj.isHighlighted ? 'kinetic-word-highlight' : ''}`}
            style={{
              display: 'inline-block',
              overflow: 'hidden', // Creates the crop/reveal effect from below
              paddingBottom: '0.1em',
              marginBottom: '-0.1em',
            }}
          >
            {wordObj.text.split('').map((char, charIndex) => (
              <motion.span
                key={charIndex}
                className="kinetic-char"
                variants={charVariants}
                style={{ display: 'inline-block', willChange: 'transform' }}
              >
                {char}
              </motion.span>
            ))}
          </span>
        );
      })}
      <div ref={laserRef} className="laser-beam" id="kinetic-laser"></div>
    </motion.h1>
  );
}
