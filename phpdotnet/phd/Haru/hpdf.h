#define FFI_LIB "/usr/lib/x86_64-linux-gnu/libhpdf-2.3.0.so"

typedef enum _HPDF_PageNumStyle {
    HPDF_PAGE_NUM_STYLE_DECIMAL = 0,
    HPDF_PAGE_NUM_STYLE_UPPER_ROMAN,
    HPDF_PAGE_NUM_STYLE_LOWER_ROMAN,
    HPDF_PAGE_NUM_STYLE_UPPER_LETTERS,
    HPDF_PAGE_NUM_STYLE_LOWER_LETTERS,
    HPDF_PAGE_NUM_STYLE_EOF
} HPDF_PageNumStyle;

typedef enum _HPDF_PageMode {
    HPDF_PAGE_MODE_USE_NONE = 0,
    HPDF_PAGE_MODE_USE_OUTLINE,
    HPDF_PAGE_MODE_USE_THUMBS,
    HPDF_PAGE_MODE_FULL_SCREEN,
/*  HPDF_PAGE_MODE_USE_OC,
    HPDF_PAGE_MODE_USE_ATTACHMENTS,
 */
    HPDF_PAGE_MODE_EOF
} HPDF_PageMode;

typedef  unsigned long       HPDF_STATUS;
typedef  unsigned int        HPDF_UINT;
typedef  unsigned short      HPDF_UINT16;

typedef void
( *HPDF_Error_Handler)  (HPDF_STATUS   error_no,
                                     HPDF_STATUS   detail_no,
                                     void         *user_data);

typedef void         *HPDF_HANDLE;
typedef HPDF_HANDLE   HPDF_Doc;

// HPDF_EXPORT is extern
extern HPDF_Doc 
HPDF_New  (HPDF_Error_Handler   user_error_fn,
           void                *user_data);

extern HPDF_STATUS
HPDF_SetCompressionMode  (HPDF_Doc    pdf,
                          HPDF_UINT   mode);

extern HPDF_STATUS
HPDF_AddPageLabel  (HPDF_Doc            pdf,
                    HPDF_UINT           page_num,
                    HPDF_PageNumStyle   style,
                    HPDF_UINT           first_page,
                    const char         *prefix);

extern HPDF_STATUS
HPDF_SetPageMode  (HPDF_Doc        pdf,
                   HPDF_PageMode   mode);

extern HPDF_STATUS
HPDF_SetPagesConfiguration  (HPDF_Doc    pdf,
                             HPDF_UINT   page_per_pages);

typedef HPDF_HANDLE   HPDF_Dict;
typedef HPDF_Dict HPDF_Font;

extern HPDF_Font
HPDF_GetFont  (HPDF_Doc     pdf,
               const char  *font_name,
               const char  *encoding_name);

extern HPDF_STATUS
HPDF_SaveToFile  (HPDF_Doc     pdf,
                  const char  *file_name);

typedef HPDF_HANDLE   HPDF_Encoder;
typedef HPDF_HANDLE   HPDF_Outline;

extern HPDF_Outline
HPDF_CreateOutline  (HPDF_Doc       pdf,
                     HPDF_Outline   parent,
                     const char    *title,
                     HPDF_Encoder   encoder);

typedef HPDF_HANDLE HPDF_Page;

extern HPDF_Page
HPDF_AddPage  (HPDF_Doc   pdf);

typedef HPDF_HANDLE   HPDF_Image;

extern HPDF_Image
HPDF_LoadPngImageFromFile (HPDF_Doc      pdf,
                            const char    *filename);

extern HPDF_Image
HPDF_LoadJpegImageFromFile (HPDF_Doc      pdf,
                            const char    *filename);

typedef enum _HPDF_TextRenderingMode {
    HPDF_FILL = 0,
    HPDF_STROKE,
    HPDF_FILL_THEN_STROKE,
    HPDF_INVISIBLE,
    HPDF_FILL_CLIPPING,
    HPDF_STROKE_CLIPPING,
    HPDF_FILL_STROKE_CLIPPING,
    HPDF_CLIPPING,
    HPDF_RENDERING_MODE_EOF
} HPDF_TextRenderingMode;

extern HPDF_STATUS
HPDF_Page_SetTextRenderingMode  (HPDF_Page               page,
                                 HPDF_TextRenderingMode  mode);

typedef  float               HPDF_REAL;

extern HPDF_STATUS
HPDF_Page_SetRGBStroke  (HPDF_Page  page,
                         HPDF_REAL  r,
                         HPDF_REAL  g,
                         HPDF_REAL  b);

extern HPDF_STATUS
HPDF_Page_SetRGBFill  (HPDF_Page  page,
                       HPDF_REAL  r,
                       HPDF_REAL  g,
                       HPDF_REAL  b);

extern HPDF_STATUS
HPDF_Page_SetFontAndSize  (HPDF_Page  page,
                           HPDF_Font  font,
                           HPDF_REAL  size);

extern HPDF_STATUS
HPDF_Page_BeginText  (HPDF_Page  page);

typedef  unsigned char       HPDF_BYTE;
typedef  signed int          HPDF_BOOL;

extern HPDF_UINT
HPDF_Font_MeasureText (HPDF_Font          font,
                       const HPDF_BYTE   *text,
                       HPDF_UINT          len,
                       HPDF_REAL          width,
                       HPDF_REAL          font_size,
                       HPDF_REAL          char_space,
                       HPDF_REAL          word_space,
                       HPDF_BOOL          wordwrap,
                       HPDF_REAL         *real_width);

extern HPDF_REAL
HPDF_Page_GetCharSpace  (HPDF_Page   page);

extern HPDF_REAL
HPDF_Page_GetWordSpace  (HPDF_Page   page);

extern HPDF_STATUS
HPDF_Page_TextOut  (HPDF_Page    page,
                    HPDF_REAL    xpos,
                    HPDF_REAL    ypos,
                    const char  *text);

extern HPDF_REAL
HPDF_Page_TextWidth  (HPDF_Page    page,
                      const char  *text);

extern HPDF_STATUS
HPDF_Page_EndText  (HPDF_Page  page);

typedef HPDF_HANDLE   HPDF_Destination;

extern HPDF_Destination
HPDF_Page_CreateDestination  (HPDF_Page   page);

extern HPDF_STATUS
HPDF_Destination_SetXYZ  (HPDF_Destination  dst,
                          HPDF_REAL         left,
                          HPDF_REAL         top,
                          HPDF_REAL         zoom);

extern HPDF_REAL
HPDF_Page_GetHeight  (HPDF_Page   page);

extern HPDF_STATUS
HPDF_Outline_SetDestination (HPDF_Outline      outline,
                             HPDF_Destination  dst);

extern HPDF_STATUS
HPDF_Outline_SetOpened  (HPDF_Outline  outline,
                         HPDF_BOOL     opened);

extern HPDF_STATUS
HPDF_Page_SetLineWidth  (HPDF_Page  page,
                         HPDF_REAL  line_width);

extern HPDF_STATUS
HPDF_Page_SetDash  (HPDF_Page           page,
                    const HPDF_UINT16  *dash_ptn,
                    HPDF_UINT           num_param,
                    HPDF_UINT           phase);

extern HPDF_STATUS
HPDF_Page_MoveTo  (HPDF_Page    page,
                   HPDF_REAL    x,
                   HPDF_REAL    y);

extern HPDF_STATUS
HPDF_Page_LineTo  (HPDF_Page    page,
                   HPDF_REAL    x,
                   HPDF_REAL    y);

extern HPDF_STATUS
HPDF_Page_Stroke  (HPDF_Page  page);

extern HPDF_STATUS
HPDF_Page_ClosePathStroke  (HPDF_Page  page);

typedef  struct _HPDF_Rect {
    HPDF_REAL  left;
    HPDF_REAL  bottom;
    HPDF_REAL  right;
    HPDF_REAL  top;
} HPDF_Rect;

typedef HPDF_HANDLE   HPDF_Annotation;

extern HPDF_Annotation
HPDF_Page_CreateURILinkAnnot  (HPDF_Page     page,
                               HPDF_Rect     rect,
                               const char   *uri);

extern HPDF_STATUS
HPDF_LinkAnnot_SetBorderStyle  (HPDF_Annotation  annot,
                                HPDF_REAL        width,
                                HPDF_UINT16      dash_on,
                                HPDF_UINT16      dash_off);

extern HPDF_STATUS
HPDF_Page_Rectangle  (HPDF_Page  page,
                      HPDF_REAL  x,
                      HPDF_REAL  y,
                      HPDF_REAL  width,
                      HPDF_REAL  height);

extern HPDF_Annotation
HPDF_Page_CreateLinkAnnot  (HPDF_Page          page,
                            HPDF_Rect          rect,
                            HPDF_Destination   dst);
