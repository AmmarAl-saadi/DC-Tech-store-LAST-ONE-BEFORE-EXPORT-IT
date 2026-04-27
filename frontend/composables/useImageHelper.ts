export const useImageHelper = () => {
  const thumbImageURL = (item: any) => {
    if (item?.image) {
      // If it starts with http, return as is
      if (item.image.startsWith('http')) return item.image;
      return `/uploads/${item.image}`;
    }
    return '/images/placeholder.png';
  };

  const categoryImage = (cat: any) => {
    if (!cat) return '/images/placeholder.png';
    
    const premiumImages: Record<string, string> = {
      'computer-systems': '/categories/cat_computer_systems.png',
      'components': '/categories/cat_components.png',
      'security-solutions': '/categories/cat_security.png',
      'point-of-sale': '/categories/cat_pos.png',
      'gaming': '/categories/cat_gaming.png',
      'connectors-and-convertors': '/categories/cat_connectors.png',
      'camera-and-recorder': '/categories/cat_camera.png',
      'network': '/categories/cat_network.png',
      'audio': '/categories/cat_audio.png',
      'accessories': '/categories/cat_accessories.png',
      'printers--supplies': '/categories/cat_printers_supplies.png',
      'cables': '/categories/cat_cables.png'
    };

    if (premiumImages[cat.slug]) {
      return premiumImages[cat.slug];
    }
    return thumbImageURL(cat);
  };

  return {
    thumbImageURL,
    categoryImage
  };
};
