import React, { useRef, useState, useEffect } from 'react';
import { Canvas, useFrame } from '@react-three/fiber';
import { Html } from '@react-three/drei';
import { motion } from 'framer-motion';

// Premium SVG Icon Registry for major web development technologies
const svgRegistry = {
  react: (
    <>
      <ellipse cx="12" cy="12" rx="11" ry="4.2" transform="rotate(30 12 12)" />
      <ellipse cx="12" cy="12" rx="11" ry="4.2" transform="rotate(90 12 12)" />
      <ellipse cx="12" cy="12" rx="11" ry="4.2" transform="rotate(150 12 12)" />
      <circle cx="12" cy="12" r="2" fill="currentColor" />
    </>
  ),
  laravel: (
    <>
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 2L4 7v10l8 5 8-5V7l-8-5z" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 22V12" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M20 7l-8 5-8-5" />
    </>
  ),
  tailwind: (
    <>
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 14c2-2 5.5-2 7.5 0s2 5.5 0 7.5-5.5 2-7.5 0" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 10c-2 2-5.5 2-7.5 0s-2-5.5 0-7.5 5.5-2 7.5 0" />
    </>
  ),
  tailwindcss: (
    <>
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 14c2-2 5.5-2 7.5 0s2 5.5 0 7.5-5.5 2-7.5 0" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 10c-2 2-5.5 2-7.5 0s-2-5.5 0-7.5 5.5-2 7.5 0" />
    </>
  ),
  csstailwind: (
    <>
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 14c2-2 5.5-2 7.5 0s2 5.5 0 7.5-5.5 2-7.5 0" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 10c-2 2-5.5 2-7.5 0s-2-5.5 0-7.5 5.5-2 7.5 0" />
    </>
  ),
  threejs: (
    <>
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 3l10 17H2L12 3z" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 3v17" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 12.5L2 20" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 12.5L22 20" />
    </>
  ),
  three: (
    <>
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 3l10 17H2L12 3z" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 3v17" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 12.5L2 20" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 12.5L22 20" />
    </>
  ),
  php: (
    <>
      <ellipse cx="12" cy="12" rx="10" ry="6" strokeWidth="1.5" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M7 9v6 M7 11.5h2a1.5 1.5 0 000-3H7" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M11 9v6 M11 12h2 M13 9v6" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M15 9v6 M15 11.5h2a1.5 1.5 0 000-3H15" />
    </>
  ),
  mysql: (
    <>
      <path strokeLinecap="round" strokeLinejoin="round" d="M4 6c0 1.66 3.58 3 8 3s8-1.34 8-3s-3.58-3-8-3s-8 1.34-8 3z" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M4 6v6c0 1.66 3.58 3 8 3s8-1.34 8-3V6" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M4 12v6c0 1.66 3.58 3 8 3s8-1.34 8-3v-6" />
    </>
  ),
  js: (
    <>
      <rect x="3" y="3" width="18" height="18" rx="4" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M9 9v5.5a1.5 1.5 0 01-3 0" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M13 14c.4.6 1 .9 1.7.9.8 0 1.3-.4 1.3-1s-.4-.7-1.1-.9c-.8-.2-1.4-.4-1.4-1.2s.5-1.1 1.3-1.1c.6 0 1.1.2 1.5.6" />
    </>
  ),
  javascript: (
    <>
      <rect x="3" y="3" width="18" height="18" rx="4" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M9 9v5.5a1.5 1.5 0 01-3 0" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M13 14c.4.6 1 .9 1.7.9.8 0 1.3-.4 1.3-1s-.4-.7-1.1-.9c-.8-.2-1.4-.4-1.4-1.2s.5-1.1 1.3-1.1c.6 0 1.1.2 1.5.6" />
    </>
  ),
  figma: (
    <>
      <path d="M8 5a3 3 0 000 6h3V5H8z" />
      <path d="M11 5a3 3 0 103 3V5h-3z" />
      <path d="M11 11a3 3 0 103-3h-3v3z" />
      <path d="M8 11a3 3 0 103 3v-3H8z" />
      <path d="M8 17a3 3 0 106 0v-3H8a3 3 0 000 3z" />
    </>
  ),
  vuejs: (
    <>
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 21l9-16h-4.5L12 13.5 7.5 5H3l9 16z" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 13.5l4-7h-3l-1 2-1-2h-3l4 7z" />
    </>
  ),
  vue: (
    <>
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 21l9-16h-4.5L12 13.5 7.5 5H3l9 16z" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 13.5l4-7h-3l-1 2-1-2h-3l4 7z" />
    </>
  ),
  git: (
    <>
      <path strokeLinecap="round" strokeLinejoin="round" d="M18 12c0 1.66-2.69 3-6 3s-6-1.34-6-3" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 2v20" />
      <circle cx="12" cy="12" r="3" />
    </>
  ),
  docker: (
    <>
      <rect x="2" y="10" width="4" height="4" rx="1" />
      <rect x="7" y="10" width="4" height="4" rx="1" />
      <rect x="12" y="10" width="4" height="4" rx="1" />
      <rect x="7" y="5" width="4" height="4" rx="1" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M2 16h20c0-4.42-3.58-8-8-8H2v8z" />
    </>
  ),
  livewire: (
    <>
      <path strokeLinecap="round" strokeLinejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
    </>
  ),
};

// Fallback premium high-tech glowing particle icon
const fallbackSvg = (
  <>
    <path strokeLinecap="round" strokeLinejoin="round" d="M12 3v18M3 12h18M12 7.5l4.5 4.5-4.5 4.5-4.5-4.5z" />
  </>
);

// Individual Tech Logo Constellation Item
const OrbitingLogo = ({ name, svgPath, speed, radius, yOffset, phase, isCyberTheme }) => {
  const groupRef = useRef();
  const [hovered, setHovered] = useState(false);

  useFrame((state) => {
    const time = state.clock.getElapsedTime();
    const angle = time * speed + phase;

    // Orbit coordinates on the X-Z plane (circular orbit with customized radius)
    const x = Math.cos(angle) * radius;
    const z = Math.sin(angle) * radius;
    
    // Elegant vertical bobbing out of phase to create an organic wave ring
    const y = Math.sin(time * 0.75 + phase * 2.0) * 0.3 + yOffset;

    if (groupRef.current) {
      groupRef.current.position.set(x, y, z);
    }
  });

  const activeColor = isCyberTheme ? '#39ff14' : '#ff5500';

  return (
    <group ref={groupRef}>
      <Html center distanceFactor={7.5} style={{ pointerEvents: 'auto' }}>
        <div
          onPointerOver={() => setHovered(true)}
          onPointerOut={() => setHovered(false)}
          className={`flex items-center gap-2 px-3 py-2 rounded-xl backdrop-blur-md transition-all duration-300 transform select-none border whitespace-nowrap bg-black/60 ${
            hovered
              ? 'scale-110 border-orange-500/80'
              : 'opacity-70 border-white/5 shadow-[0_4px_12px_rgba(0,0,0,0.5)]'
          }`}
          style={{
            borderColor: hovered ? activeColor : 'rgba(255, 255, 255, 0.05)',
            boxShadow: hovered
              ? `0 0 25px ${activeColor}55, inset 0 0 10px ${activeColor}22`
              : '0 0 10px rgba(0,0,0,0.4)',
            cursor: 'pointer',
          }}
        >
          <svg
            viewBox="0 0 24 24"
            fill="none"
            stroke={hovered ? activeColor : '#fff'}
            strokeWidth="1.8"
            className="w-5 h-5 transition-colors duration-300"
          >
            {svgPath}
          </svg>
          <span
            className="text-[10px] font-medium tracking-wider uppercase transition-colors duration-300 font-sans"
            style={{ color: hovered ? '#ffffff' : '#a1a1aa' }}
          >
            {name}
          </span>
        </div>
      </Html>
    </group>
  );
};

// Abstract mutating 3D glass dodecahedron body
const CentralObject = ({ isCyberTheme }) => {
  const meshRef = useRef();
  const wireframeRef = useRef();

  useFrame((state) => {
    const time = state.clock.getElapsedTime();
    
    // Slow rotational velocity
    const rotX = time * 0.12;
    const rotY = time * 0.16;
    
    if (meshRef.current) {
      meshRef.current.rotation.x = rotX;
      meshRef.current.rotation.y = rotY;
    }
    if (wireframeRef.current) {
      wireframeRef.current.rotation.x = rotX;
      wireframeRef.current.rotation.y = rotY;
    }

    // Breath-like mutating scale
    const scale = 1.25 + Math.sin(time * 1.8) * 0.06;
    if (meshRef.current) {
      meshRef.current.scale.set(scale, scale, scale);
    }
    if (wireframeRef.current) {
      // Keep wireframe slightly offset for glowing outline depth
      wireframeRef.current.scale.set(scale * 1.015, scale * 1.015, scale * 1.015);
    }
  });

  const activeColor = isCyberTheme ? '#39ff14' : '#ff3d00';

  return (
    <group>
      {/* 3D Dark Glass Dodecahedron Mesh */}
      <mesh ref={meshRef}>
        <dodecahedronGeometry args={[1, 1]} />
        <meshPhysicalMaterial
          color="#060606"
          roughness={0.12}
          metalness={0.9}
          transmission={0.65}
          thickness={1.4}
          transparent
          opacity={0.88}
        />
      </mesh>

      {/* Wireframe Hologram Outline Mesh */}
      <mesh ref={wireframeRef}>
        <dodecahedronGeometry args={[1, 1]} />
        <meshBasicMaterial
          color={activeColor}
          wireframe
          transparent
          opacity={0.4}
        />
      </mesh>
    </group>
  );
};

// Mouse Parallax Wrapper Scene
const ParallaxGroup = ({ children }) => {
  const groupRef = useRef();

  useFrame((state) => {
    // Parallax logic: rotate slightly based on cursor
    const targetX = -state.pointer.x * 0.28;
    const targetY = state.pointer.y * 0.28;

    if (groupRef.current) {
      groupRef.current.rotation.y += (targetX - groupRef.current.rotation.y) * 0.06;
      groupRef.current.rotation.x += (targetY - groupRef.current.rotation.x) * 0.06;
    }
  });

  return <group ref={groupRef}>{children}</group>;
};

// Main Scene Component
// Main Scene Component
export default function Hero3D({ initialSkills }) {
  const [isCyberTheme, setIsCyberTheme] = useState(false);

  // Sync color variables in real-time with theme classes
  useEffect(() => {
    const checkTheme = () => {
      setIsCyberTheme(document.documentElement.classList.contains('theme-cyber'));
    };

    checkTheme();

    const observer = new MutationObserver(checkTheme);
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

    return () => observer.disconnect();
  }, []);

  // Set up the hardware-accelerated CSS transition trigger on mounting
  useEffect(() => {
    const parent = document.getElementById('hero-3d-hologram');
    if (parent) {
      // Let the headline stagger start first, then smoothly fade-up scale the entire WebGL scene
      const timer = setTimeout(() => {
        parent.classList.add('visible');
      }, 30);
      return () => clearTimeout(timer);
    }
  }, []);

  // Set up the default skills array (fallback if database has no custom skills chosen)
  const defaultSkills = [
    { name: 'React', radius: 2.2, yOffset: 0.3 },
    { name: 'Laravel', radius: 2.0, yOffset: -0.2 },
    { name: 'Tailwind', radius: 2.3, yOffset: -0.4 },
    { name: 'Three.js', radius: 2.1, yOffset: 0.4 },
    { name: 'PHP', radius: 2.2, yOffset: 0.2 },
    { name: 'MySQL', radius: 2.0, yOffset: -0.3 },
    { name: 'JS', radius: 2.3, yOffset: -0.5 },
    { name: 'Figma', radius: 2.1, yOffset: 0.5 },
  ];

  // Map initialSkills from Laravel database, fallback to defaults if none selected/provided
  const rawSkills = (initialSkills && initialSkills.length > 0) ? initialSkills : defaultSkills;
  
  // Format the skills array with correct SVGs and dynamically calculated radii/offsets
  const techLogos = rawSkills.map((skill, index) => {
    const normalizedName = skill.name.toLowerCase().replace(/[^a-z0-9]/g, '');
    const svgPath = svgRegistry[normalizedName] || fallbackSvg;
    
    // Dynamically alternate radii between 2.0 and 2.3 to give a nice depth
    const radius = skill.radius || (2.0 + (index % 4) * 0.1);
    
    // Dynamically stagger the vertical height to prevent flat collisions
    const yOffset = skill.yOffset !== undefined ? skill.yOffset : ((index % 2 === 0 ? 0.25 : -0.3) + (index % 3) * 0.08);

    return {
      name: skill.name,
      svgPath,
      radius,
      yOffset
    };
  });

  return (
    <div className="w-full h-full relative flex items-center justify-center">
      {/* High-Performance Canvas */}
      <Canvas
        camera={{ position: [0, 0, 5.2], fov: 60 }}
        style={{
          width: '100%',
          height: '100%',
          position: 'absolute',
          top: 0,
          left: 0,
          pointerEvents: 'auto',
        }}
        gl={{ antialias: true, alpha: true }}
      >
        <ambientLight intensity={0.5} />
        <directionalLight position={[5, 10, 5]} intensity={1.5} />
        <pointLight position={[-10, -10, -10]} intensity={0.8} />

        <ParallaxGroup>
          {/* Central 3D Dark Glass Mutant */}
          <CentralObject isCyberTheme={isCyberTheme} />

          {/* Hologram Floating Constellation Logos */}
          {techLogos.map((logo, index) => {
            // Dynamically calculate phase to spread N logos perfectly 360 degrees apart
            const calculatedPhase = (index * 2 * Math.PI) / techLogos.length;
            // Uniform orbit speed so they maintain their relative spacing perfectly over time
            const baseSpeed = 0.28;

            return (
              <OrbitingLogo
                key={logo.name}
                name={logo.name}
                svgPath={logo.svgPath}
                speed={baseSpeed}
                radius={logo.radius}
                yOffset={logo.yOffset}
                phase={calculatedPhase}
                isCyberTheme={isCyberTheme}
              />
            );
          })}
        </ParallaxGroup>
      </Canvas>
    </div>
  );
}
