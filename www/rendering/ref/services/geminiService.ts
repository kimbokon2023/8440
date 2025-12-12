
import { GoogleGenAI, Part } from "@google/genai";
import { MaterialSlot, FloorMode, FloorPreset } from "../types";

// Helper to convert File to Base64
const fileToPart = async (file: File, mimeType: string): Promise<Part> => {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.onloadend = () => {
      const base64String = (reader.result as string).split(',')[1];
      resolve({
        inlineData: {
          data: base64String,
          mimeType: mimeType
        }
      });
    };
    reader.onerror = reject;
    reader.readAsDataURL(file);
  });
};

interface FloorConfig {
  mode: FloorMode;
  file: File | null;
  preset: FloorPreset;
}

export const generateElevatorRender = async (
  layoutFile: File,
  doorFile: File | null,
  floorConfig: FloorConfig,
  panels: MaterialSlot[],
  aspectRatio: string = "1:1",
  lightingTemp: number = 2000,
  reflectionIntensity: number = 50 // 0 to 100
): Promise<string> => {
  // Always create a new instance to ensure the latest selected API key is used
  const apiKey = process.env.API_KEY;
  if (!apiKey) {
    throw new Error("API Key not found. Please select a project with a valid API key.");
  }

  const ai = new GoogleGenAI({ apiKey });

  // Helper to generate reflection description based on intensity (0-100)
  const getReflectionContext = (intensity: number, type: string) => {
    let intensityDesc = "";
    
    if (intensity < 20) intensityDesc = "Very Low (Matte/Dull)";
    else if (intensity < 40) intensityDesc = "Low (Satin/Soft)";
    else if (intensity < 60) intensityDesc = "Medium (Standard)";
    else if (intensity < 80) intensityDesc = "High (Glossy)";
    else intensityDesc = "Maximum (Mirror-like)";

    // Specific logic for Mirror preset
    if (type === 'mirror') {
       if (intensity < 30) return `Use a 'Foggy/Antique Mirror' finish. The reflections should be very blurred and subtle (Intensity: ${intensity}%). Do NOT make it perfectly clear.`;
       if (intensity < 70) return `Use a 'Standard Polished' finish. Reflections are visible but slightly softened (Intensity: ${intensity}%).`;
       return `Use a 'Perfect Chrome/Mirror' finish. Reflections should be extremely sharp, clear, and high-contrast (Intensity: ${intensity}%).`;
    }

    // Logic for other metals
    if (intensity < 20) return `Finish: Matte/Flat. Minimal to no specular highlights. (Intensity: ${intensity}%)`;
    if (intensity > 80) return `Finish: High Gloss / Wet Look. Strong specular highlights and sharp reflections. (Intensity: ${intensity}%)`;
    
    return `Finish: Standard Architectural Satin/Semi-Gloss. Balanced reflections. (Intensity: ${intensity}%)`;
  };

  // Prepare the prompt parts
  const parts: Part[] = [];

  // 1. System/Context Instruction - Highly detailed
  parts.push({
    text: `You are an expert 3D architectural visualizer. 
    Your task is to generate a high-quality, photorealistic rendering of an elevator interior.
    
    PRIMARY OBJECTIVE: PRESERVE GEOMETRY
    You will be provided with a 'REFERENCE LAYOUT STRUCTURE' image. 
    This image is the absolute mask and wireframe for the scene. 
    You must NOT change the aspect ratio, the perspective lines, or the relative sizes of the panels defined in this layout.
    Even if you have generated images before, disregard them. Treat this layout image as the ONLY truth for geometry.
    `
  });

  // 2. The Layout Image - FIRST IMAGE to ensure priority
  const layoutPart = await fileToPart(layoutFile, layoutFile.type);
  parts.push({ text: "REFERENCE LAYOUT STRUCTURE (GROUND TRUTH):" });
  parts.push(layoutPart);

  // 3. The Door Material
  if (doorFile) {
    const doorPart = await fileToPart(doorFile, doorFile.type);
    parts.push({ text: "MATERIAL A (Use for Main Entrance Doors):" });
    parts.push(doorPart);
  }

  // 4. Floor Material logic
  if (floorConfig.mode === 'upload' && floorConfig.file) {
    const floorPart = await fileToPart(floorConfig.file, floorConfig.file.type);
    parts.push({ text: "MATERIAL C (Use for Floor):" });
    parts.push(floorPart);
  } else if (floorConfig.mode === 'preset') {
    // If preset, we add a text instruction
    const presetName = floorConfig.preset === 'marble' ? 'Polished Luxury Marble (High Gloss)' : 'Modern PVC Deco Tile (Matte finish)';
    parts.push({ text: `FLOOR INSTRUCTION: Render the floor using a '${presetName}' material. It should look realistic and match the perspective of the layout.` });
  }

  // 5. Panel Materials - Optimized Grouping for both Files and Presets
  
  // Maps to store grouped IDs
  const fileGroups = new Map<File, string[]>(); 
  const presetGroups = new Map<string, string[]>(); // Key: "color-type", Value: [IDs]

  for (const panel of panels) {
    if (panel.mode === 'upload' && panel.file) {
      if (!fileGroups.has(panel.file)) {
        fileGroups.set(panel.file, []);
      }
      fileGroups.get(panel.file)?.push(panel.id);
    } else if (panel.mode === 'preset') {
      const key = `${panel.presetColor}-${panel.presetType}`; // e.g. "gold-hairline"
      if (!presetGroups.has(key)) {
        presetGroups.set(key, []);
      }
      presetGroups.get(key)?.push(panel.id);
    }
  }

  let materialIndex = 1;

  // Process File Groups
  for (const [file, panelIds] of fileGroups.entries()) {
    const idsString = panelIds.join(", ");
    const part = await fileToPart(file, file.type);
    parts.push({ text: `MATERIAL B${materialIndex} (Apply this texture to Panels: ${idsString}):` });
    parts.push(part);
    // Add reflection context even for images
    const fileReflectivity = reflectionIntensity > 70 ? "Make this surface highly reflective/glossy." : "Keep this surface standard/matte.";
    parts.push({ text: `Surface Property: ${fileReflectivity}` });
    materialIndex++;
  }

  // Process Preset Groups
  for (const [key, panelIds] of presetGroups.entries()) {
    const idsString = panelIds.join(", ");
    const [color, type] = key.split('-'); // e.g. "gold", "hairline"
    
    // Construct readable description
    const colorName = color.charAt(0).toUpperCase() + color.slice(1);
    let typeName = '';
    let typeDesc = '';

    switch (type) {
      case 'hairline':
        typeName = 'Hairline (Brushed)';
        typeDesc = 'fine linear brush marks, anisotropic reflection';
        break;
      case 'mirror':
        typeName = 'Mirror (Polished)';
        typeDesc = 'flat surface'; // Reflection level is handled by getReflectionContext
        break;
      case 'vibration':
        typeName = 'Vibration (Non-directional)';
        typeDesc = 'non-directional circular polish marks, swirling vibration texture';
        break;
      case 'bead':
        typeName = 'Bead Blast (Matte)';
        typeDesc = 'frosted, diffuse non-reflective finish, sandblasted appearance';
        break;
      default:
        typeName = 'Standard Metal';
        typeDesc = 'metallic finish';
    }

    const reflectionInstruction = getReflectionContext(reflectionIntensity, type);
    
    parts.push({ 
      text: `MATERIAL B${materialIndex} (Apply to Panels: ${idsString}): 
      Texture: ${colorName} Stainless Steel with ${typeName} finish. 
      Appearance: Realistic architectural metal material, ${color} tone, ${typeDesc}.
      REFLECTIVITY INSTRUCTION: ${reflectionInstruction}` 
    });
    materialIndex++;
  }

  // 6. Final Synthesis Prompt
  parts.push({
    text: `GENERATE THE IMAGE NOW.
    
    LIGHTING & ATMOSPHERE INSTRUCTIONS:
    - Lighting Color Temperature: ${lightingTemp}K (Kelvin).
    - If < 3000K: Create a warm, cozy, amber-toned atmosphere typical of luxury hotels. Use soft, golden indirect lighting.
    - If > 4000K: Create a neutral, crisp, bright white daylight atmosphere.
    - If > 6000K: Create a cool, bluish, clinical or very modern high-tech atmosphere.
    - Ensure the LED lighting fixtures in the ceiling (if any) or indirect coves emit light matching this ${lightingTemp}K color.
    
    STRICT EXECUTION STEPS:
    1. LOAD GEOMETRY: Trace the exact lines from 'REFERENCE LAYOUT STRUCTURE'. Do not widen or stretch the room. Keep the original image's aspect ratio logic.
    2. APPLY MATERIALS:
       - Map 'MATERIAL A' to the entrance doors.
       - ${floorConfig.mode === 'upload' && floorConfig.file ? "Map 'MATERIAL C' to the floor." : "Render the floor based on the FLOOR INSTRUCTION provided above."}
       - Map 'MATERIAL B...' (Images or Text Definitions) to their specific panel numbers (1-11) as listed above.
       - If multiple panels share a material, ensure the texture is tiled or stretched seamlessly across them, maintaining the visual flow.
    3. RENDER STYLE:
       - Use global illumination, soft shadows, and reflective surfaces typical of a luxury elevator.
       - The result must look like a 3D Max V-Ray render.
    
    Output ONLY the rendered image.`
  });

  try {
    // Upgraded to 'gemini-3-pro-image-preview' for high-quality generation
    const response = await ai.models.generateContent({
      model: 'gemini-3-pro-image-preview',
      contents: {
        parts: parts
      },
      config: {
        imageConfig: {
          aspectRatio: aspectRatio,
          imageSize: "2K" // Using 2K resolution for Pro model
        }
      }
    });

    // Extract image from response
    if (response.candidates && response.candidates[0].content.parts) {
      for (const part of response.candidates[0].content.parts) {
        if (part.inlineData && part.inlineData.data) {
          return `data:${part.inlineData.mimeType || 'image/png'};base64,${part.inlineData.data}`;
        }
      }
    }
    
    throw new Error("No image generated in the response.");

  } catch (error) {
    console.error("Gemini Generation Error:", error);
    throw error;
  }
};
