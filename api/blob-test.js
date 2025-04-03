// /api/blob-test.js
import { put } from '@vercel/blob';

export const config = {
  api: {
    bodyParser: false,
  },
};

export default async function handler(req, res) {
  if (req.method !== 'POST') {
    return res.status(405).send('Method Not Allowed');
  }

  try {
    const { url } = await put('test-file.txt', 'Hello from X-Plorer!', {
      access: 'public',
    });

    return res.status(200).json({ success: true, url });
  } catch (error) {
    console.error('Test Blob upload error:', error);
    return res.status(500).json({ error: error.message || 'Upload failed' });
  }
}
